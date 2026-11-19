<?php

namespace App\Http\Controllers;

use App\Models\Leitor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeitorController extends Controller
{
    // Lista os leitores, com pesquisa por nome (Etapa 9)
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        $leitores = Leitor::when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('leitores.index', compact('leitores', 'busca'));
    }

    public function create()
    {
        return view('leitores.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate($this->regras(), $this->mensagens(), $this->nomes());

        Leitor::create($dados);

        return redirect()->route('leitores.index')
            ->with('sucesso', 'Leitor cadastrado com sucesso!');
    }

    public function edit(Leitor $leitor)
    {
        return view('leitores.edit', compact('leitor'));
    }

    public function update(Request $request, Leitor $leitor)
    {
        $dados = $request->validate($this->regras($leitor), $this->mensagens(), $this->nomes());

        $leitor->update($dados);

        return redirect()->route('leitores.index')
            ->with('sucesso', 'Leitor atualizado com sucesso!');
    }

    // Só exclui se o leitor não tiver empréstimos (Etapa 5, item 4)
    public function destroy(Leitor $leitor)
    {
        if ($leitor->emprestimos()->exists()) {
            return redirect()->route('leitores.index')
                ->withErrors(['leitor' => 'Este leitor não pode ser excluído porque possui empréstimos registrados.']);
        }

        $leitor->delete();

        return redirect()->route('leitores.index')
            ->with('sucesso', 'Leitor excluído com sucesso!');
    }

    private function regras(?Leitor $leitor = null): array
    {
        return [
            'nome'     => 'required|string|max:255',
            'cpf'      => ['required', 'string', 'max:14',
                           Rule::unique('leitores', 'cpf')->ignore($leitor?->id)],
            'email'    => ['required', 'email', 'max:255',
                           Rule::unique('leitores', 'email')->ignore($leitor?->id)],
            'telefone' => 'required|string|max:30',
        ];
    }

    private function mensagens(): array
    {
        return [
            'required'     => 'O campo :attribute é obrigatório.',
            'max'          => 'O campo :attribute deve ter no máximo :max caracteres.',
            'email.email'  => 'Informe um e-mail em formato válido.',
            'cpf.unique'   => 'Já existe um leitor cadastrado com este CPF.',
            'email.unique' => 'Já existe um leitor cadastrado com este e-mail.',
        ];
    }

    private function nomes(): array
    {
        return [
            'cpf'   => 'CPF',
            'email' => 'e-mail',
        ];
    }
}