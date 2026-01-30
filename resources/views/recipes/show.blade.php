@extends('layouts.app')

@section('title', 'Detalhes da Receita')

@section('content')
    <style>
        .recipe-hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            align-items: center;
        }
        .recipe-hero-media {
            background: rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 12px;
        }
        .recipe-hero-media img {
            width: 100%;
            border-radius: 10px;
            display: block;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }
        .recipe-details {
            display: grid;
            grid-template-columns: 1.6fr 0.8fr;
            gap: 20px;
            align-items: start;
        }
        .ingredients-box {
            background: #f7f4f1;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 14px;
        }
        .ingredients-box ul {
            margin: 8px 0 0;
            padding-left: 16px;
            font-size: 13px;
            color: var(--muted);
            word-break: break-word;
        }
        .ingredients-box li {
            margin-bottom: 6px;
        }
        @media (max-width: 900px) {
            .recipe-hero-grid {
                grid-template-columns: 1fr;
            }
            .recipe-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @if($recipe)
        <section class="hero">
            <div class="wrap recipe-hero-grid">
                <div>
                    <div style="font-size:20px; font-weight:600;">{{ $recipe['titulo'] }}</div>
                    <p style="margin:6px 0 0; font-size:13px; opacity:.9;">{{ $recipe['descricao'] }}</p>
                </div>
                <div class="recipe-hero-media">
                    @if(!empty($recipe['foto_url']))
                        <img src="{{ $recipe['foto_url'] }}" alt="Foto da receita" />
                    @else
                        <div style="text-align:center;">Sem imagem</div>
                    @endif
                </div>
            </div>
        </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <div class="recipe-details">
                    <div>
                        <h2>Prepara-te para Cozinhar</h2>
                        <ul style="margin:8px 0 0; padding-left:18px; font-size:13px; color:var(--muted); list-style:disc;">
                            @php
                                $passos = preg_split("/\r\n|\r|\n/", (string) ($recipe['modo_preparo'] ?? ''));
                            @endphp
                            @foreach($passos as $passo)
                                @if(trim($passo) !== '')
                                    <li style="margin-bottom:6px;">{{ $passo }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="ingredients-box">
                        <strong>Ingredientes</strong>
                        <ul>
                            @php
                                $ingredientes = preg_split("/\r\n|\r|\n/", (string) ($recipe['ingredientes'] ?? ''));
                            @endphp
                            @foreach($ingredientes as $item)
                                @if(trim($item) !== '')
                                    <li>{{ $item }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @if(!empty($recipe['dificuldade']))
                    <div style="margin-top:16px;">
                        <span class="pill">{{ ucfirst($recipe['dificuldade']) }}</span>
                    </div>
                @endif
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
                            <form method="post" action="{{ route('reports.store', $comment['id']) }}" style="margin-top:6px;" data-confirm-title="Denunciar comentário" data-confirm-message="Tem certeza que deseja denunciar este comentário?">
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
