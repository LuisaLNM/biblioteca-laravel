@extends('layouts.app')
@section('title', 'Editar leitor')

@section('content')
<h1>Editar leitor</h1>

<form method="POST" action="{{ route('leitores.update', $leitor) }}">
    @csrf
    @method('PUT')
    @include('leitores._form')
    <div class="mt-3">
        <button class="btn btn-success">Salvar alterações</button>
        <a href="{{ route('leitores.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection
