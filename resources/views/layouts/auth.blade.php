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
            --accent: #6755a4;
            --accent-dark: #55448b;
            --card: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--ink);
        }
        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            width: 100%;
        }
        .content {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 40px;
            padding: 72px 6vw;
            width: 100%;
            min-height: 100vh;
            background: var(--card);
            align-items: center;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 34px;
            margin: 0 0 18px;
        }
        label {
            display: block;
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e5e1dc;
            border-radius: 6px;
            font-size: 15px;
            margin-bottom: 16px;
        }
        .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            border: 0;
            border-radius: 6px;
            padding: 12px 18px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn.wide {
            min-width: 140px;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-ghost {
            background: #ebe7f4;
            color: var(--accent-dark);
        }
        .links {
            margin-top: 10px;
            font-size: 13px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
        }
        .links a { color: var(--accent-dark); text-decoration: none; }
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
        }
        @media (max-width: 780px) {
            .content {
                grid-template-columns: 1fr;
                padding: 32px 24px;
                gap: 24px;
                min-height: 100vh;
                text-align: center;
            }
            form { width: min(420px, 100%); margin: 0 auto; }
            .brand { order: -1; margin-bottom: 8px; }
            .links { flex-direction: column; gap: 6px; align-items: center; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="content">
            @yield('content')
            <div class="brand">
                <x-brand />
            </div>
        </div>
    </div>
</body>
</html>
