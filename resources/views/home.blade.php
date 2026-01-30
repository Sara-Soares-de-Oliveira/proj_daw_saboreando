@extends('layouts.app')

@section('title', 'Pagina Inicial - Explorador')
@section('hide-top-search', true)

@section('content')
    <style>
        .h-scroll {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(220px, 1fr);
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 6px;
            scroll-snap-type: x mandatory;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.15) transparent;
        }
        .h-scroll > * {
            scroll-snap-align: start;
        }
        .h-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .h-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .h-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 999px;
        }
    </style>
    <section class="hero">
        <div class="wrap">
            <h1>Bem-vindo ao Saboreando</h1>
            <div class="hero-box">
                <p style="margin:0 0 10px; font-size:13px;">Digite o que queres comer hoje ou navegue nas receitas</p>
                <form action="{{ route('search.results') }}" method="get">
                    <input class="wide" type="text" name="q" placeholder="Escreva aqui" style="margin:0 0 12px; padding:10px 12px; border-radius:6px; border:0;" />
                    <button class="btn btn-search">Pesquisar</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Nao Saem da Boca da Malta</h2>
            <p>Descubra as receitas favoritas da comunidade</p>
            <div class="h-scroll">
                @forelse($popular as $recipe)
                    <a class="panel recipe-card" href="{{ route('recipes.show', $recipe['id']) }}">
                        @if(!empty($recipe['foto_url']))
                            <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" style="width:100%; height:140px; object-fit:cover; border-radius:8px; margin-bottom:10px;" />
                        @endif
                        <div class="recipe-title">{{ $recipe['titulo'] }}</div>
                        <div class="recipe-desc">{{ $recipe['descricao'] }}</div>
                        @if(!empty($recipe['dificuldade']))
                            <div class="recipe-meta">
                                <span class="pill">{{ ucfirst($recipe['dificuldade']) }}</span>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="panel"><div>Sem receitas</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Acabadas de Sair do Forno</h2>
            <p>Explore novas receitas acabadas de adicionar</p>
            <div class="h-scroll">
                @forelse($newest as $recipe)
                    <a class="panel recipe-card" href="{{ route('recipes.show', $recipe['id']) }}">
                        @if(!empty($recipe['foto_url']))
                            <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" style="width:100%; height:140px; object-fit:cover; border-radius:8px; margin-bottom:10px;" />
                        @endif
                        <div class="recipe-title">{{ $recipe['titulo'] }}</div>
                        <div class="recipe-desc">{{ $recipe['descricao'] }}</div>
                        <div class="recipe-meta">
                            @if(!empty($recipe['dificuldade']))
                                <span class="pill">{{ ucfirst($recipe['dificuldade']) }}</span>
                            @endif
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
