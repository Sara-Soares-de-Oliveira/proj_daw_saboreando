@extends('layouts.app')

@section('title', 'Pagina Inicial - Moderador')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Bem-vindo ao Saboreando</h1>
            <div class="hero-box">
                <p style="margin:0 0 10px; font-size:13px;">Explore o conteudo e valide receitas pendentes</p>
                <input class="wide" type="text" placeholder="Escreva aqui" style="margin:0 0 12px; padding:10px 12px; border-radius:6px; border:0;" />
                <button class="btn btn-primary">Pesquisar</button>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Nao Saem da Boca da Malta</h2>
            <p>Receitas mais populares</p>
            <div class="grid">
                <div class="card">
                    <div>Ervilhas com Ovos Escalfados</div>
                    <small>Receita</small>
                </div>
                <div class="card">
                    <div>Tapioca com Queijo</div>
                    <small>Receita</small>
                </div>
                <div class="card">
                    <div>Lasanha Tradicional</div>
                    <small>Receita</small>
                </div>
            </div>
        </div>
    </section>
@endsection
