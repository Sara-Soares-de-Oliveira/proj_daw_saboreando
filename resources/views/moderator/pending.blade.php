@extends('layouts.app')

@section('title', 'Receitas Pendentes')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">Nome de utilizador</div>
                <div style="font-size:20px; font-weight:600;">Receitas Pendentes</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="list">
                <div class="panel">
                    <div class="row" style="align-items:flex-start;">
                        <div>
                            <strong>Sopa de Primavera</strong><br />
                            <small>Receita resumida com ingredientes e modo.</small>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary">Sim</button>
                            <button class="btn" style="background:#111; color:#fff;">Nao</button>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="row" style="align-items:flex-start;">
                        <div>
                            <strong>Lasanha com Camarao</strong><br />
                            <small>Receita resumida com ingredientes e modo.</small>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary">Sim</button>
                            <button class="btn" style="background:#111; color:#fff;">Nao</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
