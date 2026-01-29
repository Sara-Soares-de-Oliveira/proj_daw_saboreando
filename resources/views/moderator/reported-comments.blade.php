@extends('layouts.app')

@section('title', 'Comentarios Denunciados')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">Nome de utilizador</div>
                <div style="font-size:20px; font-weight:600;">Comentarios Denunciados</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="list">
                <div class="panel">
                    <div class="row">
                        <div>
                            <strong>Joana Frade</strong><br />
                            <small>Comentario ofensivo reportado.</small>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary">Manter</button>
                            <button class="btn" style="background:#111; color:#fff;">Excluir</button>
                        </div>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>
                            <strong>Tiago Mota</strong><br />
                            <small>Comentario inadequado reportado.</small>
                        </div>
                        <div class="row">
                            <button class="btn btn-primary">Manter</button>
                            <button class="btn" style="background:#111; color:#fff;">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
