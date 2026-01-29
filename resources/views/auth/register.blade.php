@extends('layouts.auth')

@section('title', 'Registar-se')

@section('content')
    <div>
        <h1>Registre-se e torne-se parte da comunidade</h1>
        <form method="post" action="{{ route('auth.register.submit') }}">
            @csrf
            <label for="name">Nome do utilizador</label>
            <input id="name" name="name" type="text" placeholder="Digite o seu nome" />

            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="Digite o seu email" />

            <label for="password">Palavra-passe</label>
            <input id="password" name="password" type="password" placeholder="Digite a sua palavra-passe" />

            <div class="row">
                <button type="submit" class="btn btn-primary wide">Registar-se</button>
                <a class="btn btn-ghost wide" href="/entrar">Entrar</a>
            </div>
        </form>
    </div>
@endsection
