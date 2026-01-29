@extends('layouts.app')

@section('title', 'Criar Receita')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Compartilhe sua Receita</h1>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <h2>Detalhes da Receita</h2>
                <p style="color:var(--muted); font-size:13px;">Preencha os campos abaixo com os ingredientes e modo de preparo</p>

                <div class="form-grid" style="margin-top:16px;">
                    <div>
                        <label>Nome da Receita</label>
                        <input type="text" placeholder="Digite o nome" />
                    </div>
                    <div>
                        <label>Imagem da Receita</label>
                        <div class="panel" style="text-align:center; background:#f5f2ef;">
                            <div style="font-size:12px; color:var(--muted);">Foto (opcional)</div>
                        </div>
                    </div>
                    <div>
                        <label>Ingredientes</label>
                        <textarea placeholder="Liste os ingredientes"></textarea>
                    </div>
                    <div>
                        <label>Modo de Preparo</label>
                        <textarea placeholder="Descreva o modo de preparo"></textarea>
                    </div>
                    <div>
                        <label>Nivel de Dificuldade</label>
                        <div class="chips">
                            <span class="chip">Facil</span>
                            <span class="chip">Medio</span>
                            <span class="chip">Dificil</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary">Enviar Receita</button>
                </div>
            </div>
        </div>
    </section>
@endsection
