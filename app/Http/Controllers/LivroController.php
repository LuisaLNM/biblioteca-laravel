<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    // Lista os livros, com pesquisa por título ou autor (Etapa 9)
    public function index(Request $request)
    {
        $busca = $request->input('busca');

        $livros = Livro::when($busca, function ($query, $busca) {
                $query->where('titulo', 'like', "%{$busca}%")
                      ->orWhere('autor', 'like', "%{$busca}%");
            })
            ->orderBy('titulo')
            ->paginate(10)
            ->withQueryString();

        return view('livros.index', compact('livros', 'busca'));
    }

    // Mostra o formulário de cadastro
    public function create()
    {
        return view('livros.create');
    }

    // Valida e grava o novo livro
    public function store(Request $request)
    {
        $dados = $request->validate($this->regras(), $this->mensagens(), $this->nomes());

        Livro::create($dados);

        return redirect()->route('livros.index')
            ->with('sucesso', 'Livro cadastrado com sucesso!');
    }

    // Mostra o formulário de edição já preenchido
    public function edit(Livro $livro)
    {
        return view('livros.edit', compact('livro'));
    }

    // Valida e salva as alterações
    public function update(Request $request, Livro $livro)
    {
        $dados = $request->validate($this->regras($livro), $this->mensagens(), $this->nomes());

        $livro->update($dados);

        return redirect()->route('livros.index')
            ->with('sucesso', 'Livro atualizado com sucesso!');
    }

    // Exclui o livro, se ele não tiver empréstimos
    public function destroy(Livro $livro)
    {
        if ($livro->emprestimos()->exists()) {
            return redirect()->route('livros.index')
                ->withErrors(['livro' => 'Este livro não pode ser excluído porque possui empréstimos registrados.']);
        }

        $livro->delete();

        return redirect()->route('livros.index')
            ->with('sucesso', 'Livro excluído com sucesso!');
    }

    // Regras de validação (iguais para cadastrar e editar)
    private function regras(?Livro $livro = null): array
    {
        return [
            'titulo'         => 'required|string|max:255',
            'autor'          => 'required|string|max:255',
            'editora'        => 'nullable|string|max:255',
            'ano_publicacao' => 'nullable|integer|min:1000|max:' . date('Y'),
            'isbn'           => ['required', 'string', 'max:30',
                                 Rule::unique('livros', 'isbn')->ignore($livro?->id)],
            'quantidade'     => 'required|integer|min:0',
        ];
    }

    private function mensagens(): array
    {
        return [
            'required'           => 'O campo :attribute é obrigatório.',
            'integer'            => 'O campo :attribute deve ser um número inteiro.',
            'max'                => 'O campo :attribute deve ter no máximo :max caracteres.',
            'ano_publicacao.min' => 'Informe um ano de publicação válido.',
            'ano_publicacao.max' => 'O ano de publicação não pode ser maior que o ano atual.',
            'isbn.unique'        => 'Já existe um livro cadastrado com este ISBN.',
            'quantidade.min'     => 'A quantidade não pode ser negativa.',
        ];
    }

    private function nomes(): array
    {
        return [
            'titulo'         => 'título',
            'ano_publicacao' => 'ano de publicação',
            'isbn'           => 'ISBN',
        ];
    }
}