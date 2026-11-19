@extends('layouts.app')
@section('title', 'Novo leitor')

@section('content')
<h1>Novo leitor</h1>

<form method="POST" action="{{ route('leitores.store') }}">
    @csrf
    @include('leitores._form')
    <div class="mt-3">
        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('leitores.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection