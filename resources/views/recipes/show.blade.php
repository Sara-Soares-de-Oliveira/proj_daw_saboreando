@extends('layouts.app')

@section('title', 'Detalhes da Receita')

@section('content')
    <section class="hero">
        <div class="wrap" style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:20px; align-items:center;">
            <div>
                <div style="font-size:20px; font-weight:600;">Batata Gratinada</div>
                <p style="margin:6px 0 0; font-size:13px; opacity:.9;">Receita cremosa com queijo e especiarias.</p>
            </div>
            <div class="panel" style="background:rgba(255,255,255,0.2); color:#fff;">
                <div style="text-align:center;">Imagem</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <div class="row" style="align-items:flex-start;">
                    <div style="flex:1;">
                        <h2>Prepara-te para Cozinhar</h2>
                        <p style="color:var(--muted); font-size:13px;">Instrucoes e ingredientes da receita.</p>
                    </div>
                    <div style="min-width:220px;">
                        <strong>Ingredientes</strong>
                        <ul style="margin:8px 0 0; padding-left:16px; font-size:13px; color:var(--muted);">
                            <li>Batatas</li>
                            <li>Queijo</li>
                            <li>Natas</li>
                        </ul>
                    </div>
                </div>
                <div style="margin-top:16px; font-size:13px; color:var(--muted);">
                    Modo de preparo detalhado aqui.
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <h2>Comentarios</h2>
                <p style="color:var(--muted); font-size:13px;">Deixe um comentario sobre a receita.</p>
                <textarea class="wide" placeholder="Escreva o seu comentario" style="min-height:120px;"></textarea>
                <div class="form-actions">
                    <button class="btn btn-primary">Enviar Comentario</button>
                </div>
            </div>
        </div>
    </section>
@endsection
