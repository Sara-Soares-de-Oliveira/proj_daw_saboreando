@extends('layouts.auth')

@section('title', 'Nova Palavra-Passe')

@section('content')
    <div>
        <h1>Alterar Palavra - Passe</h1>
        <form>
            <label for="email">Email</label>
            <input id="email" type="email" placeholder="Digite o seu email" />

            <label for="new_password">Nova palavra-passe</label>
            <input id="new_password" type="password" placeholder="Digite a nova palavra-passe" />

            <div class="row">
                <button type="submit" class="btn btn-primary wide">Confirmar</button>
                <a class="btn btn-ghost wide" href="/entrar">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
