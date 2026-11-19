@extends('layouts.app')
@section('title', 'Leitores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Leitores</h1>
    <a class="btn btn-primary" href="{{ route('leitores.create') }}">Novo leitor</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6">
        <input name="busca" value="{{ $busca }}" class="form-control" placeholder="Pesquisar por nome">
    </div>
    <div class="col-auto">
        <button class="btn btn-secondary">Pesquisar</button>
        <a href="{{ route('leitores.index') }}" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<table class="table table-striped align-middle">
    <thead>
        <tr><th>Nome</th><th>CPF</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr>
    </thead>
    <tbody>
    @forelse($leitores as $leitor)
        <tr>
            <td>{{ $leitor->nome }}</td>
            <td>{{ $leitor->cpf }}</td>
            <td>{{ $leitor->email }}</td>
            <td>{{ $leitor->telefone }}</td>
            <td>
                <a class="btn btn-sm btn-warning" href="{{ route('leitores.edit', $leitor) }}">Editar</a>
                <form method="POST" action="{{ route('leitores.destroy', $leitor) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir este leitor?')">Excluir</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">Nenhum leitor encontrado.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $leitores->links() }}
@endsection
