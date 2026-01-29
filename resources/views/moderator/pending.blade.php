@extends('layouts.app')

@section('title', 'Receitas Pendentes')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">{{ $user['name'] ?? 'Moderador' }}</div>
                <div style="font-size:20px; font-weight:600;">Receitas Pendentes</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="list">
                @forelse($recipes as $recipe)
                    <div class="panel">
                        <div class="row" style="align-items:flex-start;">
                            <div>
                                <strong>{{ $recipe['titulo'] }}</strong><br />
                                <small>{{ $recipe['descricao'] }}</small>
                            </div>
                            <div class="row">
                                <form method="post" action="{{ route('moderator.recipes.approve', $recipe['id']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-primary">Sim</button>
                                </form>
                                <form method="post" action="{{ route('moderator.recipes.reject', $recipe['id']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn" style="background:#111; color:#fff;">Nao</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="panel">Sem receitas pendentes.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
