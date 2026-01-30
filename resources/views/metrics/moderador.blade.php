@extends('layouts.app')

@section('title', 'Metricas - Moderador')

@section('content')
    <style>
        .chart {
            display: grid;
            gap: 10px;
        }
        .bar-row {
            display: grid;
            grid-template-columns: 180px 1fr 60px;
            align-items: center;
            gap: 12px;
            font-size: 12px;
        }
        .bar-track {
            height: 10px;
            background: #efe8fb;
            border-radius: 999px;
            overflow: hidden;
        }
        .bar-fill {
            height: 100%;
            background: #6b1bd8;
        }
        .spark {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(24px, 1fr));
            gap: 6px;
            align-items: end;
            min-height: 90px;
            padding: 8px 0;
        }
        .spark-bar {
            background: #c9b6f7;
            border-radius: 4px 4px 2px 2px;
            min-height: 6px;
        }
        .spark-labels {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(24px, 1fr));
            gap: 6px;
            font-size: 10px;
            color: var(--muted);
        }
    </style>
    <section class="hero">
        <div class="wrap">
            <h1>Metricas do Sistema</h1>
            <div class="hero-box">
                <p style="margin:0 0 10px; font-size:13px;">Escolha um periodo para ver dados globais</p>
                <form method="get" action="{{ route('metrics.moderador') }}" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                    <select name="period" style="padding:8px 12px; border-radius:6px; border:0;">
                        <option value="day" @selected($period === 'day')>Dia</option>
                        <option value="week" @selected($period === 'week')>Semana</option>
                        <option value="month" @selected($period === 'month')>Mes</option>
                        <option value="year" @selected($period === 'year')>Ano</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Aplicar</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Estado Atual do Sistema</h2>
            <div class="list">
                <div class="panel">
                    <div class="row">
                        <div>Receitas pendentes</div>
                        <strong>{{ $metrics['recipes_pendentes'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Receitas aprovadas</div>
                        <strong>{{ $metrics['recipes_aprovadas'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Comentarios removidos</div>
                        <strong>{{ $metrics['comments_removidos'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Participacao de Utilizadores</h2>
            <div class="panel">
                @php
                    $participacao = [
                        'Criadores' => (int) ($metrics['period_users_creators'] ?? 0),
                        'Interagem' => (int) ($metrics['period_users_interactors'] ?? 0),
                        'So visualizam' => (int) ($metrics['period_users_viewers_only'] ?? 0),
                    ];
                    $maxParticipacao = max($participacao ?: [0]);
                @endphp
                <div class="chart">
                    @foreach($participacao as $label => $count)
                        @php
                            $width = $maxParticipacao > 0 ? (int) round(($count / $maxParticipacao) * 100) : 0;
                        @endphp
                        <div class="bar-row">
                            <div>{{ $label }}</div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $width }}%"></div>
                            </div>
                            <div style="text-align:right;">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Receitas Aprovadas vs Rejeitadas</h2>
            <div class="panel">
                @php
                    $aprov = (int) ($metrics['period_recipes_approved'] ?? 0);
                    $rej = (int) ($metrics['period_recipes_rejected'] ?? 0);
                    $maxRecipes = max($aprov, $rej, 1);
                @endphp
                <div class="chart">
                    @foreach(['Aprovadas' => $aprov, 'Rejeitadas' => $rej] as $label => $count)
                        @php
                            $width = $maxRecipes > 0 ? (int) round(($count / $maxRecipes) * 100) : 0;
                        @endphp
                        <div class="bar-row">
                            <div>{{ $label }}</div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $width }}%"></div>
                            </div>
                            <div style="text-align:right;">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Comentarios Removidos vs Mantidos</h2>
            <div class="panel">
                @php
                    $rem = (int) ($metrics['period_comments_removed'] ?? 0);
                    $keep = (int) ($metrics['period_comments_kept'] ?? 0);
                    $maxComments = max($rem, $keep, 1);
                @endphp
                <div class="chart">
                    @foreach(['Removidos' => $rem, 'Mantidos' => $keep] as $label => $count)
                        @php
                            $width = $maxComments > 0 ? (int) round(($count / $maxComments) * 100) : 0;
                        @endphp
                        <div class="bar-row">
                            <div>{{ $label }}</div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $width }}%"></div>
                            </div>
                            <div style="text-align:right;">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Picos por Hora</h2>
            <div class="panel">
                @php
                    $peak = $metrics['period_peak_hours'] ?? [];
                    $peakLogins = $peak['logins'] ?? [];
                    $peakRecipes = $peak['recipes'] ?? [];
                    $peakReports = $peak['reports'] ?? [];

                    $peakIndex = [];
                    foreach ($peakLogins as $row) {
                        $hour = (int) $row['hour'];
                        $peakIndex[$hour] = ['hour' => $hour, 'logins' => (int) $row['total'], 'recipes' => 0, 'reports' => 0];
                    }
                    foreach ($peakRecipes as $row) {
                        $hour = (int) $row['hour'];
                        $peakIndex[$hour] = $peakIndex[$hour] ?? ['hour' => $hour, 'logins' => 0, 'recipes' => 0, 'reports' => 0];
                        $peakIndex[$hour]['recipes'] = (int) $row['total'];
                    }
                    foreach ($peakReports as $row) {
                        $hour = (int) $row['hour'];
                        $peakIndex[$hour] = $peakIndex[$hour] ?? ['hour' => $hour, 'logins' => 0, 'recipes' => 0, 'reports' => 0];
                        $peakIndex[$hour]['reports'] = (int) $row['total'];
                    }
                    ksort($peakIndex);
                    $peakList = array_values($peakIndex);
                    $maxPeak = 0;
                    foreach ($peakList as $row) {
                        $maxPeak = max($maxPeak, $row['logins'], $row['recipes'], $row['reports']);
                    }
                @endphp
                <div class="chart">
                    @forelse($peakList as $row)
                        @php
                            $label = str_pad((string) $row['hour'], 2, '0', STR_PAD_LEFT) . 'h';
                            $count = $row['logins'] + $row['recipes'] + $row['reports'];
                            $width = $maxPeak > 0 ? (int) round(($count / $maxPeak) * 100) : 0;
                        @endphp
                        <div class="bar-row">
                            <div>{{ $label }}</div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $width }}%"></div>
                            </div>
                            <div style="text-align:right;">{{ $count }}</div>
                        </div>
                    @empty
                        <div style="font-size:13px; color:var(--muted);">Sem dados de picos.</div>
                    @endforelse
                </div>
                <div style="margin-top:10px; font-size:12px; color:var(--muted);">
                    Total por hora = logins + receitas + denuncias
                </div>
            </div>
        </div>
    </section>
@endsection
