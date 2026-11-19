<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Leitor;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprestimoController extends Controller
{
    // Lista os empréstimos, com filtro por situação e por atrasados (Etapas 7 e 9)
    public function index(Request $request)
    {
        // with() já traz o livro e o leitor de cada empréstimo, evitando várias consultas
        $query = Emprestimo::with(['livro', 'leitor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('atrasados')) {
            $query->whereNull('data_devolucao')
                  ->whereDate('data_devolucao_prevista', '<', today());
        }

        $emprestimos = $query->orderByDesc('data_emprestimo')
                             ->orderByDesc('id')
                             ->paginate(10)
                             ->withQueryString();

        return view('emprestimos.index', compact('emprestimos'));
    }

    // Formulário: só aparecem livros com exemplares disponíveis
    public function create()
    {
        $leitores = Leitor::orderBy('nome')->get();
        $livros   = Livro::where('quantidade', '>', 0)->orderBy('titulo')->get();

        return view('emprestimos.create', compact('leitores', 'livros'));
    }

    // Registra o empréstimo (Etapa 6)
    public function store(Request $request)
    {
        $dados = $request->validate([
            'leitor_id'               => 'required|exists:leitores,id',
            'livro_id'                => 'required|exists:livros,id',
            'data_emprestimo'         => 'required|date',
            'data_devolucao_prevista' => 'required|date|after_or_equal:data_emprestimo',
        ], [
            'leitor_id.required'      => 'Selecione o leitor.',
            'livro_id.required'       => 'Selecione o livro.',
            'exists'                  => 'O item selecionado não existe.',
            'required'                => 'O campo :attribute é obrigatório.',
            'date'                    => 'Informe uma data válida em :attribute.',
            'data_devolucao_prevista.after_or_equal'
                                      => 'A data prevista de devolução não pode ser anterior à data do empréstimo.',
        ], [
            'data_emprestimo'         => 'data do empréstimo',
            'data_devolucao_prevista' => 'data prevista de devolução',
        ]);

        DB::transaction(function () use ($dados) {
            // lockForUpdate "trava" o livro até o fim da transação,
            // para dois empréstimos simultâneos não pegarem o último exemplar
            $livro = Livro::whereKey($dados['livro_id'])->lockForUpdate()->firstOrFail();

            if ($livro->quantidade < 1) {
                throw ValidationException::withMessages([
                    'livro_id' => 'Não há exemplares disponíveis deste livro.',
                ]);
            }

            $livro->decrement('quantidade'); // diminui 1 unidade

            Emprestimo::create([
                ...$dados,
                'status' => 'Em andamento',
            ]);
        });

        return redirect()->route('emprestimos.index')
            ->with('sucesso', 'Empréstimo registrado com sucesso!');
    }

    // Registra a devolução (Etapa 7)
    public function devolver(Emprestimo $emprestimo)
    {
        DB::transaction(function () use ($emprestimo) {
            $emprestimo = Emprestimo::whereKey($emprestimo->id)->lockForUpdate()->firstOrFail();

            // Impede devolver duas vezes
            if ($emprestimo->status === 'Devolvido') {
                throw ValidationException::withMessages([
                    'emprestimo' => 'Este empréstimo já foi devolvido.',
                ]);
            }

            $livro = Livro::whereKey($emprestimo->livro_id)->lockForUpdate()->firstOrFail();

            $emprestimo->update([
                'data_devolucao' => today(),   // data atual
                'status'         => 'Devolvido',
            ]);

            $livro->increment('quantidade'); // devolve 1 unidade ao estoque
        });

        return redirect()->route('emprestimos.index')
            ->with('sucesso', 'Devolução registrada com sucesso!');
    }
}