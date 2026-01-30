@extends('layouts.app')

@section('title', 'Metricas - Explorador')

@section('content')
    <style>
        .chart {
            display: grid;
            gap: 10px;
        }
        .bar-row {
            display: grid;
            grid-template-columns: 140px 1fr 60px;
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
            grid-template-columns: repeat(auto-fit, minmax(20px, 1fr));
            gap: 6px;
            align-items: end;
            min-height: 80px;
            padding: 8px 0;
        }
        .spark-bar {
            background: #c9b6f7;
            border-radius: 4px 4px 2px 2px;
        }
    </style>
    <section class="hero">
        <div class="wrap">
            <h1>Metricas Pessoais</h1>
            <div class="hero-box">
                <p style="margin:0 0 10px; font-size:13px;">Escolha um periodo para ver os seus dados</p>
                <form method="get" action="{{ route('metrics.explorador') }}" style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                    <select name="period" style="padding:8px 12px; border-radius:6px; border:0;">
                        <option value="day" @selected($period === 'day')>Dia</option>
                        <option value="week" @selected($period === 'week')>Semana</option>
                        <option value="month" @selected($period === 'month')>Mes</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Aplicar</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Resumo</h2>
            <div class="grid">
                <div class="card">
                    <div>Tempo total na plataforma (seg)</div>
                    <strong>{{ $metrics['total_time_seconds'] ?? 0 }}</strong>
                </div>
                <div class="card">
                    <div>Receitas criadas (total)</div>
                    <strong>{{ $metrics['recipes_total'] ?? 0 }}</strong>
                </div>
                <div class="card">
                    <div>Receitas aprovadas</div>
                    <strong>{{ $metrics['recipes_aprovadas'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Consumo</h2>
            <div class="list">
                <div class="panel">
                    <div class="row">
                        <div>Receitas visualizadas (periodo)</div>
                        <strong>{{ $metrics['period_views'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Tempo medio por receita (seg)</div>
                        <strong>{{ $metrics['period_avg_view_seconds'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Comentarios feitos (total)</div>
                        <strong>{{ $metrics['comments_made'] ?? 0 }}</strong>
                    </div>
                </div>
                <div class="panel">
                    <div class="row">
                        <div>Comentarios recebidos (total)</div>
                        <strong>{{ $metrics['comments_received'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <h2>Receitas mais vistas</h2>
            <div class="panel">
                @php
                    $top = $metrics['top_approved_recipes'] ?? [];
                    $maxViews = 0;
                    foreach ($top as $item) {
                        $maxViews = max($maxViews, (int) ($item['views_count'] ?? 0));
                    }
                @endphp
                <div class="chart">
                    @forelse($top as $item)
                        @php
                            $count = (int) ($item['views_count'] ?? 0);
                            $width = $maxViews > 0 ? (int) round(($count / $maxViews) * 100) : 0;
                        @endphp
                        <div class="bar-row">
                            <div>{{ $item['titulo'] ?? ('Receita '.$item['recipe_id']) }}</div>
                            <div class="bar-track">
                                <div class="bar-fill" style="width: {{ $width }}%"></div>
                            </div>
                            <div style="text-align:right;">{{ $count }}</div>
                        </div>
                    @empty
                        <div style="font-size:13px; color:var(--muted);">Sem dados de visualizacao.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
