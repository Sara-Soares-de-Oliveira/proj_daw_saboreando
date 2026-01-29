@extends('layouts.app')

@section('title', 'Editar Receita')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Atualize a sua Receita</h1>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <h2>Detalhes da Receita</h2>
                <p style="color:var(--muted); font-size:13px;">Atualize os campos e guarde as alteracoes</p>

                <form method="post" action="{{ route('recipes.update', $recipe['id']) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="current_foto" value="{{ $recipe['foto'] }}">
                    <div class="form-grid" style="margin-top:16px;">
                        <div>
                            <label>Nome da Receita</label>
                            <input name="titulo" type="text" value="{{ $recipe['titulo'] }}" />
                        </div>
                        <div>
                            <label>Imagem da Receita</label>
                            <input name="foto_file" type="file" accept="image/*" />
                        </div>
                        <div>
                            <label>Descricao</label>
                            <textarea name="descricao">{{ $recipe['descricao'] }}</textarea>
                        </div>
                        <div>
                            <label>Ingredientes</label>
                            <textarea name="ingredientes">{{ $recipe['ingredientes'] }}</textarea>
                        </div>
                        <div>
                            <label>Modo de Preparo</label>
                            <textarea name="modo_preparo">{{ $recipe['modo_preparo'] }}</textarea>
                        </div>
                        <div>
                            <label>Nivel de Dificuldade</label>
                            <select name="dificuldade" style="width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:6px;">
                                <option value="facil" @selected($recipe['dificuldade'] === 'facil')>Facil</option>
                                <option value="medio" @selected($recipe['dificuldade'] === 'medio')>Medio</option>
                                <option value="dificil" @selected($recipe['dificuldade'] === 'dificil')>Dificil</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary">Guardar Alteracoes</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
