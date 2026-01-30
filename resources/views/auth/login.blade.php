@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')
    <div>
        <h1>Mergulhe nos sabores</h1>
        <form method="post" action="{{ route('auth.login.submit') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="Digite o seu email" />

            <label for="password">Palavra-passe</label>
            <input id="password" name="password" type="password" placeholder="Digite a sua palavra-passe" />

            <div class="row">
                <button type="submit" class="btn btn-primary wide">Entrar</button>
            </div>

            <div class="links">
                <span>Não tens conta? <a href="/registar">Regista-te</a></span>
                <a href="/nova-palavra-passe">Esqueceu a senha?</a>
            </div>
        </form>
    </div>
@endsection
