@extends('layouts.app')
@section('title', 'Painel')

@section('content')
<h1 class="mb-4">Painel da Biblioteca</h1>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-bg-primary h-100"><div class="card-body">
            <h6 class="card-title">Livros cadastrados</h6>
            <h2>{{ $totalLivros }}</h2>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success h-100"><div class="card-body">
            <h6 class="card-title">Leitores cadastrados</h6>
            <h2>{{ $totalLeitores }}</h2>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning h-100"><div class="card-body">
            <h6 class="card-title">Empréstimos em andamento</h6>
            <h2>{{ $emAndamento }}</h2>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-danger h-100"><div class="card-body">
            <h6 class="card-title">Empréstimos atrasados</h6>
            <h2>{{ $atrasados }}</h2>
        </div></div>
    </div>
</div>
@endsection