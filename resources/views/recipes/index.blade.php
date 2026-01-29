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
                        <strong>{{ $recipe['titulo'] }}</strong><br />
                        <small>{{ $recipe['descricao'] }}</small>
                        <div class="row" style="margin-top:10px;">
                            <a class="btn btn-primary" href="{{ route('recipes.show', $recipe['id']) }}">Ver</a>
                            <a class="btn" href="{{ route('recipes.edit', $recipe['id']) }}">Editar</a>
                            <form method="post" action="{{ route('recipes.destroy', $recipe['id']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn" style="background:#111; color:#fff;">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="panel">Ainda não tens receitas.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
