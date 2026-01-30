@extends('layouts.app')

@section('title', 'Comentarios Denunciados')

@section('content')
    <section class="hero">
        <div class="wrap row" style="color:#fff;">
            <div>
                <div style="font-size:13px; opacity:.85;">{{ $user['name'] ?? 'Moderador' }}</div>
                <div style="font-size:20px; font-weight:600;">Comentarios Denunciados</div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            @if($errors->has('comment'))
                <div class="panel" style="background:#fff3f3; border-color:#f2c7c7; color:#b42318; margin-bottom:12px;">
                    {{ $errors->first('comment') }}
                </div>
            @endif
            <div class="list">
                @forelse($reports as $report)
                    <div class="panel">
                        <div class="row">
                            <div>
                                <strong>{{ $report['reporter']['name'] ?? 'Utilizador' }}</strong><br />
                                <small>{{ $report['motivo'] ?? '' }}</small>
                            </div>
                            <div class="row">
                                <form method="post" action="{{ route('moderator.comments.keep', $report['comment_id']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-primary">Manter</button>
                                </form>
                                <form method="post" action="{{ route('moderator.comments.remove', $report['comment_id']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn" style="background:#111; color:#fff;">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="panel">Sem comentarios denunciados.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
