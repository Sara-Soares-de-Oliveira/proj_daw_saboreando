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
            <style>
                .pending-card {
                    max-height: 320px;
                    overflow-y: auto;
                }
            </style>
            <div class="list">
                @forelse($recipes as $recipe)
                    <div class="panel pending-card">
                        <div style="display:flex; gap:12px; align-items:flex-start; margin-bottom:10px;">
                            @if(!empty($recipe['foto_url']))
                                <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" style="width:96px; height:96px; object-fit:cover; border-radius:8px;" />
                            @endif
                            <div>
                                <strong>{{ $recipe['titulo'] }}</strong><br />
                                <small>{{ $recipe['descricao'] }}</small>
                                <div style="margin-top:6px; font-size:12px; color:var(--muted);">
                                    Dificuldade: {{ $recipe['dificuldade'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div style="font-size:12px; color:var(--muted); margin-bottom:4px;">Ingredientes</div>
                        <div style="font-size:13px; margin-bottom:10px; white-space:pre-line;">{{ $recipe['ingredientes'] ?? '' }}</div>
                        <div style="font-size:12px; color:var(--muted); margin-bottom:4px;">Modo de preparo</div>
                        <div style="font-size:13px; white-space:pre-line;">{{ $recipe['modo_preparo'] ?? '' }}</div>

                        <div class="row" style="margin-top:12px;">
                            <form method="post" action="{{ route('moderator.recipes.approve', $recipe['id']) }}" data-confirm-title="Aprovar receita" data-confirm-message="Tem certeza que deseja aprovar esta receita?">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-primary">Sim</button>
                            </form>
                            <form method="post" action="{{ route('moderator.recipes.reject', $recipe['id']) }}" data-confirm-title="Rejeitar receita" data-confirm-message="Tem certeza que deseja rejeitar esta receita?">
                                @csrf
                                @method('PATCH')
                                <button class="btn" style="background:#111; color:#fff;">Nao</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="panel">Sem receitas pendentes.</div>
                @endforelse
            </div>
            @if(method_exists($recipes, 'hasPages') && $recipes->hasPages())
                <div class="row" style="margin-top:16px; justify-content:center; gap:10px;">
                    @if($recipes->previousPageUrl())
                        <a class="btn" href="{{ $recipes->previousPageUrl() }}">Anterior</a>
                    @endif
                    @if($recipes->nextPageUrl())
                        <a class="btn btn-primary" href="{{ $recipes->nextPageUrl() }}">Seguinte</a>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
