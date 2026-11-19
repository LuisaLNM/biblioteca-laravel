@extends('layouts.app')
@section('title', 'Editar livro')

@section('content')
<h1>Editar livro</h1>

<form method="POST" action="{{ route('livros.update', $livro) }}">
    @csrf
    @method('PUT')
    @include('livros._form')
    <div class="mt-3">
        <button class="btn btn-success">Salvar alterações</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsections