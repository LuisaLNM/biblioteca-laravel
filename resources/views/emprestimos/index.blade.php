@extends('layouts.app')
@section('title', 'Empréstimos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Empréstimos</h1>
    <a class="btn btn-primary" href="{{ route('emprestimos.create') }}">Novo empréstimo</a>
</div>

{{-- Filtros (Etapa 9) --}}
<form method="GET" class="row g-2 align-items-center mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Todas as situações</option>
            <option value="Em andamento" @selected(request('status') == 'Em andamento')>Em andamento</option>
            <option value="Devolvido" @selected(request('status') == 'Devolvido')>Devolvido</option>
        </select>
    </div>
    <div class="col-auto">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="atrasados" value="1" id="atrasados"
                   @checked(request()->boolean('atrasados'))>
            <label class="form-check-label" for="atrasados">Somente atrasados</label>
        </div>
    </div>
    <div class="col-auto">
        <button class="btn btn-secondary">Filtrar</button>
        <a href="{{ route('emprestimos.index') }}" class="btn btn-outline-secondary">Limpar</a>
    </div>
</form>

<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Leitor</th><th>Livro</th><th>Empréstimo</th>
            <th>Devolução prevista</th><th>Devolvido em</th><th>Situação</th><th>Ação</th>
        </tr>
    </thead>
    <tbody>
    @forelse($emprestimos as $emprestimo)
        @php
            $atrasado = is_null($emprestimo->data_devolucao)
                        && $emprestimo->data_devolucao_prevista->lt(today());
        @endphp
        <tr>
            <td>{{ $emprestimo->leitor->nome }}</td>
            <td>{{ $emprestimo->livro->titulo }}</td>
            <td>{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</td>
            <td>{{ $emprestimo->data_devolucao_prevista->format('d/m/Y') }}</td>
            <td>{{ $emprestimo->data_devolucao?->format('d/m/Y') ?? '—' }}</td>
            <td>
                @if($emprestimo->status === 'Devolvido')
                    <span class="badge text-bg-success">Devolvido</span>
                @else
                    <span class="badge text-bg-warning">Em andamento</span>
                @endif
                @if($atrasado)
                    <span class="badge text-bg-danger">Atrasado</span>
                @endif
            </td>
            <td>
                @if($emprestimo->status === 'Em andamento')
                    <form method="POST" action="{{ route('emprestimos.devolver', $emprestimo) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-success"
                                onclick="return confirm('Confirmar a devolução deste livro?')">
                            Registrar Devolução
                        </button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7">Nenhum empréstimo encontrado.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $emprestimos->links() }}
@endsection