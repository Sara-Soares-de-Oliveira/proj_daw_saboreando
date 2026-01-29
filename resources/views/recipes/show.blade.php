@extends('layouts.app')

@section('title', 'Detalhes da Receita')

@section('content')
    @if($recipe)
        <section class="hero">
            <div class="wrap" style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:20px; align-items:center;">
                <div>
                    <div style="font-size:20px; font-weight:600;">{{ $recipe['titulo'] }}</div>
                    <p style="margin:6px 0 0; font-size:13px; opacity:.9;">{{ $recipe['descricao'] }}</p>
                </div>
                <div class="panel" style="background:rgba(255,255,255,0.2); color:#fff;">
                    @if(!empty($recipe['foto_url']))
                        <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" style="width:100%; border-radius:6px;" />
                    @else
                        <div style="text-align:center;">Sem imagem</div>
                    @endif
                </div>
            </div>
        </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <div class="row" style="align-items:flex-start;">
                    <div style="flex:1;">
                        <h2>Prepara-te para Cozinhar</h2>
                        <p style="color:var(--muted); font-size:13px;">{{ $recipe['modo_preparo'] ?? '' }}</p>
                    </div>
                    <div style="min-width:220px;">
                        <strong>Ingredientes</strong>
                        <ul style="margin:8px 0 0; padding-left:16px; font-size:13px; color:var(--muted);">
                            <li>{{ $recipe['ingredientes'] ?? '' }}</li>
                        </ul>
                    </div>
                </div>
                <div style="margin-top:16px; font-size:13px; color:var(--muted);">
                    Dificuldade: {{ $recipe['dificuldade'] ?? '-' }}
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <h2>Comentarios</h2>
                <p style="color:var(--muted); font-size:13px;">Deixe um comentario sobre a receita.</p>
                @forelse($comments as $comment)
                    <div style="border-bottom:1px solid var(--line); padding:10px 0;">
                        <strong>{{ $comment['user']['name'] ?? 'Utilizador' }}</strong>
                        <div style="font-size:13px; color:var(--muted);">{{ $comment['conteudo'] }}</div>
                        @if($canComment)
                            <form method="post" action="{{ route('reports.store', $comment['id']) }}" style="margin-top:6px;">
                                @csrf
                                <input type="text" name="motivo" placeholder="Motivo da denuncia" style="width:100%; padding:8px 10px; border:1px solid var(--line); border-radius:6px; font-size:12px; margin-bottom:6px;" />
                                <button class="btn" style="background:#111; color:#fff;">Denunciar</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div style="color:var(--muted); font-size:13px;">Ainda nao existem comentarios.</div>
                @endforelse

                @if($canComment)
                    <form method="post" action="{{ route('comments.store', $recipe['id']) }}">
                        @csrf
                        <textarea class="wide" name="conteudo" placeholder="Escreva o seu comentario" style="min-height:120px;"></textarea>
                        <div class="form-actions">
                            <button class="btn btn-primary">Enviar Comentario</button>
                        </div>
                    </form>
                @else
                    <div style="margin-top:12px; font-size:13px; color:var(--muted);">Entre para comentar.</div>
                @endif
            </div>
        </div>
    </section>
    @else
        <section class="section">
            <div class="wrap">
                <div class="panel">Receita não encontrada.</div>
            </div>
        </section>
    @endif
@endsection
