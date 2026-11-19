@extends('layouts.app')
@section('title', 'Novo livro')

@section('content')
<h1>Novo livro</h1>

<form method="POST" action="{{ route('livros.store') }}">
    @csrf
    @include('livros._form')
    <div class="mt-3">
        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection