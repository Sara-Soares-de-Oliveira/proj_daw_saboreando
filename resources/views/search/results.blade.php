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
                    <a class="card" href="{{ route('recipes.show', $recipe['id']) }}">
                        <div>{{ $recipe['titulo'] }}</div>
                        <small>{{ $recipe['descricao'] }}</small>
                    </a>
                @empty
                    <div class="card"><div>Sem resultados</div></div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
