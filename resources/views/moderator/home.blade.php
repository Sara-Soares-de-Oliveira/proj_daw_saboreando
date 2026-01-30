@extends('layouts.app')

@section('title', 'Pagina Inicial - Moderador')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Bem-vindo ao Saboreando</h1>
            <div class="hero-box">
                <p style="margin:0; font-size:13px;">Explore o conteudo e valide receitas pendentes</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Nao Saem da Boca da Malta</h2>
            <p>Receitas mais populares</p>
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
            <h2>Preview de Metricas</h2>
            <p>Indicadores globais do dia</p>
            <div class="list">
                <div class="panel">
                    <div class="row">
                        <div>Utilizadores ativos</div>
                        <strong>{{ $metrics['period_active_users'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Receitas submetidas</div>
                        <strong>{{ $metrics['period_recipes_created'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Denuncias</div>
                        <strong>{{ $metrics['period_reports_created'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <a class="btn btn-primary" href="{{ route('metrics.moderador') }}">Ver metricas detalhadas</a>
            </div>
        </div>
    </section>
@endsection
