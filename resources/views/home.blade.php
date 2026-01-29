@extends('layouts.app')

@section('title', 'Pagina Inicial - Explorador')
@section('hide-top-search', true)

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Bem-vindo ao Saboreando</h1>
            <div class="hero-box">
                <p style="margin:0 0 10px; font-size:13px;">Digite o que queres comer hoje ou navegue nas receitas</p>
                <form action="{{ route('search.results') }}" method="get">
                    <input class="wide" type="text" name="q" placeholder="Escreva aqui" style="margin:0 0 12px; padding:10px 12px; border-radius:6px; border:0;" />
                    <button class="btn btn-primary">Pesquisar</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Nao Saem da Boca da Malta</h2>
            <p>Descubra as receitas favoritas da comunidade</p>
            <div class="grid">
                @forelse($popular as $recipe)
                    <a class="card" href="{{ route('recipes.show', $recipe['id']) }}">
                        <div>{{ $recipe['titulo'] }}</div>
                        <small>{{ $recipe['descricao'] }}</small>
                    </a>
                @empty
                    <div class="card"><div>Sem receitas</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Acabadas de Sair do Forno</h2>
            <p>Explore novas receitas acabadas de adicionar</p>
            <div class="list">
                @forelse($newest as $recipe)
                    <a class="panel" href="{{ route('recipes.show', $recipe['id']) }}">
                        <div class="row">
                            <div>
                                <strong>{{ $recipe['titulo'] }}</strong><br />
                                <small>{{ $recipe['descricao'] }}</small>
                            </div>
                            <span class="pill">Novo</span>
                        </div>
                    </a>
                @empty
                    <div class="panel">Sem receitas novas.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
