@extends('layouts.app')

@section('title', 'Minhas Receitas')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">{{ $user['name'] ?? 'Utilizador' }}</div>
                <div style="font-size:20px; font-weight:600;">Minhas Receitas</div>
            </div>
            <a class="btn btn-primary" href="/receitas/criar">Criar Receita</a>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Receitas Criadas por mim</h2>
            <div class="list">
                @forelse($recipes as $recipe)
                    <div class="panel">
                        <a href="{{ route('recipes.show', $recipe['id']) }}" style="display:block;">
                            @if(!empty($recipe['foto_url']))
                                <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" style="width:100%; height:160px; object-fit:cover; border-radius:8px; margin-bottom:10px;" />
                            @endif
                            <strong>{{ $recipe['titulo'] }}</strong>
                        </a>
                        <a href="{{ route('recipes.edit', $recipe['id']) }}" style="margin-top:6px; font-size:12px; color:var(--muted); display:inline-block;">Editar</a>
                    </div>
                @empty
                    <div class="panel">Ainda não tens receitas.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Preview de Metricas</h2>
            <p>Resumo das tuas receitas</p>
            <div class="list">
                <div class="panel">
                    <div class="row">
                        <div>Receitas criadas</div>
                        <strong>{{ $metrics['recipes_total'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Receitas aprovadas</div>
                        <strong>{{ $metrics['recipes_aprovadas'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Popularidade (visualizacoes)</div>
                        <strong>{{ $metrics['views_total'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <a class="btn btn-primary" href="{{ route('metrics.explorador') }}">Ver metricas detalhadas</a>
            </div>
        </div>
    </section>
@endsection
