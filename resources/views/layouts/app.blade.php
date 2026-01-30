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
            grid-template-columns: auto 1fr auto;
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
        .btn-search {
            background: #2aa988;
            color: #fff;
        }
        .btn-search:hover { background: #238e73; }
        .menu {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            font-size: 13px;
            color: var(--muted);
            align-items: center;
            flex-wrap: wrap;
            justify-self: end;
        }
        .menu .menu-user {
            color: var(--ink);
            font-weight: 600;
            font-size: 13px;
        }
        .menu .menu-link {
            color: var(--muted);
        }
        .menu .menu-btn {
            border: 1px solid var(--line);
            background: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            color: var(--ink);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .menu .menu-btn:hover {
            border-color: #d6d1cb;
            background: #f7f4f1;
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
        .recipe-card {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 260px;
        }
        .recipe-card .recipe-meta {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .recipe-card .recipe-meta .pill {
            padding: 4px 10px;
            font-size: 11px;
        }
        .recipe-card .recipe-title {
            font-weight: 600;
            line-height: 1.3;
        }
        .recipe-card .recipe-desc {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.4;
            min-height: calc(1.4em * 2);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
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
        .dialog {
            border: 0;
            border-radius: 12px;
            padding: 18px;
            width: min(420px, 92vw);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }
        .dialog::backdrop {
            background: rgba(0,0,0,0.4);
        }
        .dialog h3 {
            margin: 0 0 8px;
            font-size: 16px;
        }
        .dialog p {
            margin: 0 0 14px;
            font-size: 13px;
            color: var(--muted);
        }
        .dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
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
<body data-success-message="{{ session('success') }}">
    <header>
        <div class="wrap nav">
            <div class="logo">Saboreando</div>
            @hasSection('hide-top-search')
            @else
                <div class="search">
                    <form action="{{ route('search.results') }}" method="get" style="display:flex; gap:10px; width:100%; justify-content:center;">
                        <input type="text" name="q" placeholder="O que queres comer hoje?" />
                        <button class="btn btn-search" type="submit">Pesquisar</button>
                    </form>
                </div>
            @endif
            <nav class="menu">
                @if(session('user') && (session('user.role') ?? '') === 'moderador')
                    <a class="menu-btn" href="/moderador">Inicio</a>
                @else
                    <a class="menu-btn" href="/home">Inicio</a>
                @endif
                @if(session('user') && (session('user.role') ?? '') === 'moderador')
                    <a class="menu-btn" href="/moderador/pendentes">Receitas Pendentes</a>
                    <a class="menu-btn" href="/moderador/comentarios-denunciados">Comentarios Denunciados</a>
                @else
                    <a class="menu-btn" href="/minhas-receitas">Minhas Receitas</a>
                @endif
                @if(session('user'))
                    <form action="{{ route('auth.logout') }}" method="post" style="display:inline;">
                        @csrf
                        <button class="btn menu-btn" type="submit">Sair</button>
                    </form>
                    <span class="menu-user">{{ session('user.name') }}</span>
                @else
                    <a class="menu-btn" href="/entrar">Entrar</a>
                @endif
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div id="success-inline" class="wrap" style="margin-top:12px; color:#1a7f37;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="wrap" style="margin-top:12px; color:#b42318;">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')

    <dialog id="confirm-dialog" class="dialog">
        <form method="dialog">
            <h3 id="confirm-title">Confirmação</h3>
            <p id="confirm-message">Tem certeza que deseja continuar?</p>
            <div class="dialog-actions">
                <button class="btn menu-btn" value="cancel">Não</button>
                <button class="btn btn-primary" value="confirm">Sim</button>
            </div>
        </form>
    </dialog>

    <dialog id="success-dialog" class="dialog">
        <form method="dialog">
            <h3>Sucesso</h3>
            <p id="success-message">{{ session('success') }}</p>
            <div class="dialog-actions">
                <button class="btn btn-primary">Ok</button>
            </div>
        </form>
    </dialog>

    <script>
        (function () {
            var confirmDialog = document.getElementById('confirm-dialog');
            var successDialog = document.getElementById('success-dialog');
            var successMessage = document.body.getAttribute('data-success-message');

            if (successDialog && successMessage) {
                var messageEl = document.getElementById('success-message');
                if (messageEl) {
                    messageEl.textContent = successMessage;
                }
                var inline = document.getElementById('success-inline');
                if (inline) {
                    inline.style.display = 'none';
                }
                if (typeof successDialog.showModal === 'function') {
                    successDialog.showModal();
                } else {
                    alert(successMessage);
                }
            }

            if (!confirmDialog) {
                return;
            }

            document.addEventListener('submit', function (event) {
                var form = event.target;
                if (!form || !form.matches('[data-confirm-message]')) {
                    return;
                }
                event.preventDefault();
                var titleEl = document.getElementById('confirm-title');
                var messageEl = document.getElementById('confirm-message');
                var title = form.getAttribute('data-confirm-title') || 'Confirmação';
                var message = form.getAttribute('data-confirm-message') || 'Tem certeza que deseja continuar?';
                if (titleEl) {
                    titleEl.textContent = title;
                }
                if (messageEl) {
                    messageEl.textContent = message;
                }
                if (typeof confirmDialog.showModal !== 'function') {
                    if (confirm(message)) {
                        form.submit();
                    }
                    return;
                }
                confirmDialog.showModal();
                confirmDialog.addEventListener('close', function onClose() {
                    confirmDialog.removeEventListener('close', onClose);
                    if (confirmDialog.returnValue === 'confirm') {
                        form.submit();
                    }
                });
            });
        })();
    </script>
</body>
</html>
