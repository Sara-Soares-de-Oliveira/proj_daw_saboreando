<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Saboreando</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:600,700|inter:400,500,600|dancing-script:600,700" rel="stylesheet" />
    <style>
        :root {
            --bg: #f7f2ed;
            --ink: #1b1b18;
            --muted: #6f6a62;
            --accent: #6b1bd8;
            --accent-dark: #5a12c6;
            --accent-soft: #f1e9ff;
            --card: #ffffff;
            --line: #e6e2dd;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }
        a { color: inherit; text-decoration: none; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
        header {
            border-bottom: 1px solid var(--line);
            background: #fff;
        }
        .nav {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 16px;
            align-items: center;
            padding: 12px 0;
        }
        .logo {
            font-family: 'Dancing Script', cursive;
            font-size: 32px;
            font-weight: 700;
        }
        .search {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }
        .search input {
            width: min(460px, 100%);
            padding: 9px 12px;
            border: 1px solid var(--line);
            border-radius: 999px;
            font-size: 14px;
        }
        .btn {
            border: 0;
            border-radius: 999px;
            padding: 9px 16px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-dark); }
        .menu {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            font-size: 13px;
            color: var(--muted);
        }
        .hero {
            background: var(--accent);
            color: #fff;
            padding: 36px 0 42px;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin: 0 0 14px;
            text-align: center;
        }
        .hero .hero-box {
            margin: 0 auto;
            max-width: 520px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 18px;
            text-align: center;
        }
        .section { padding: 28px 0; }
        .section h2 {
            font-size: 16px;
            margin: 0 0 8px;
        }
        .section p { margin: 0 0 18px; color: var(--muted); font-size: 13px; }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .card {
            background: #f0eeec;
            border-radius: 8px;
            padding: 18px;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #2f2b26;
        }
        .card small { color: var(--muted); }
        .list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .pill {
            background: var(--accent-soft);
            color: var(--accent-dark);
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 12px;
        }
        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 18px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form-grid label {
            font-size: 12px;
            color: var(--muted);
            display: block;
            margin-bottom: 6px;
        }
        .form-grid input, .form-grid textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
        }
        .form-grid textarea { min-height: 140px; resize: vertical; }
        .form-actions { text-align: center; margin-top: 16px; }
        .chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .chip {
            border: 1px solid var(--line);
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            background: #fff;
        }
        .wide {
            width: 100%;
        }
        @media (max-width: 900px) {
            .nav { grid-template-columns: 1fr; text-align: center; }
            .menu { justify-content: center; flex-wrap: wrap; }
            .grid { grid-template-columns: 1fr; }
            .list { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div class="wrap nav">
            <div class="logo">Saboreando</div>
            @hasSection('hide-top-search')
            @else
                <div class="search">
                    <form action="{{ route('search.results') }}" method="get" style="display:flex; gap:10px; width:100%; justify-content:center;">
                        <input type="text" name="q" placeholder="O que queres comer hoje?" />
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </form>
                </div>
            @endif
            <nav class="menu">
                @if(session('user') && (session('user.role') ?? '') === 'moderador')
                    <a href="/moderador">Inicio</a>
                @else
                    <a href="/home">Inicio</a>
                @endif
                @if(session('user') && (session('user.role') ?? '') === 'moderador')
                    <a href="/moderador/pendentes">Receitas Pendentes</a>
                    <a href="/moderador/comentarios-denunciados">Comentarios Denunciados</a>
                @else
                    <a href="/minhas-receitas">Minhas Receitas</a>
                @endif
                @if(session('user'))
                    <span>{{ session('user.name') }}</span>
                    <form action="{{ route('auth.logout') }}" method="post" style="display:inline;">
                        @csrf
                        <button class="btn" style="padding:6px 12px;">Sair</button>
                    </form>
                @else
                    <a href="/entrar">Entrar</a>
                @endif
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="wrap" style="margin-top:12px; color:#1a7f37;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="wrap" style="margin-top:12px; color:#b42318;">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')
</body>
</html>
