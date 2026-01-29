@extends('layouts.app')

@section('title', 'Minhas Receitas')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">Nome de utilizador</div>
                <div style="font-size:20px; font-weight:600;">Minhas Receitas</div>
            </div>
            <a class="btn btn-primary" href="/receitas/criar">Criar Receita</a>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Receitas Criadas por mim</h2>
            <div class="list">
                <div class="panel">
                    <strong>Massa Carbonara</strong><br />
                    <small>Receita italiana simples e cremosa.</small>
                </div>
                <div class="panel">
                    <strong>Bolo de Chocolate</strong><br />
                    <small>Chocolate rico com cobertura.</small>
                </div>
            </div>
        </div>
    </section>
@endsection
