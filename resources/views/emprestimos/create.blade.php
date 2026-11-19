@extends('layouts.app')
@section('title', 'Novo empréstimo')

@section('content')
<h1>Registrar empréstimo</h1>

@if($livros->isEmpty())
    <div class="alert alert-warning">Não há livros com exemplares disponíveis no momento.</div>
@endif

<form method="POST" action="{{ route('emprestimos.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Leitor *</label>
            <select name="leitor_id" class="form-select">
                <option value="">Selecione...</option>
                @foreach($leitores as $leitor)
                    <option value="{{ $leitor->id }}" @selected(old('leitor_id') == $leitor->id)>
                        {{ $leitor->nome }} ({{ $leitor->cpf }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Livro *</label>
            <select name="livro_id" class="form-select">
                <option value="">Selecione...</option>
                @foreach($livros as $livro)
                    <option value="{{ $livro->id }}" @selected(old('livro_id') == $livro->id)>
                        {{ $livro->titulo }} ({{ $livro->quantidade }} disponíveis)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Data do empréstimo *</label>
            <input type="date" name="data_emprestimo" class="form-control"
                   value="{{ old('data_emprestimo', date('Y-m-d')) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Data prevista de devolução *</label>
            <input type="date" name="data_devolucao_prevista" class="form-control"
                   value="{{ old('data_devolucao_prevista', date('Y-m-d', strtotime('+7 days'))) }}">
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-success">Confirmar empréstimo</button>
        <a href="{{ route('emprestimos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection