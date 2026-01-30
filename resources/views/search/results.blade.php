@extends('layouts.app')

@section('title', 'Resultados da Pesquisa')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Resultados da pesquisa: "{{ $term }}"</h1>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="grid">
                @forelse($recipes as $recipe)
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
                    <div class="card"><div>Sem resultados</div></div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
