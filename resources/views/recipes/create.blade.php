@extends('layouts.app')

@section('title', 'Criar Receita')

@section('content')
    <section class="hero">
        <div class="wrap">
            <h1>Compartilhe sua Receita</h1>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="panel">
                <h2>Detalhes da Receita</h2>
                <p style="color:var(--muted); font-size:13px;">Preencha os campos abaixo com os ingredientes e modo de preparo</p>

                <form method="post" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-grid" style="margin-top:16px;">
                        <div>
                            <label>Nome da Receita</label>
                            <input name="titulo" type="text" placeholder="Digite o nome" />
                        </div>
                        <div>
                            <label>Imagem da Receita</label>
                            <input id="foto_file" name="foto_file" type="file" accept="image/*" />
                            <img id="foto_preview" alt="Preview da receita" style="margin-top:10px; width:100%; max-height:220px; object-fit:cover; border-radius:8px; display:none;" />
                        </div>
                        <div>
                            <label>Descricao</label>
                            <textarea name="descricao" placeholder="Descreva a receita"></textarea>
                        </div>
                        <div>
                            <label>Ingredientes</label>
                            <div id="ingredientes-list" style="display:grid; gap:8px;">
                                <input name="ingredientes[]" type="text" placeholder="Ex: 2 ovos" />
                            </div>
                            <div class="row" style="margin-top:8px; gap:8px;">
                                <button type="button" id="add-ingrediente" style="border:0; background:transparent; color:var(--muted); font-size:12px;">+ adicionar</button>
                                <button type="button" id="remove-ingrediente" style="border:0; background:transparent; color:var(--muted); font-size:12px;">- remover</button>
                            </div>
                        </div>
                        <div>
                            <label>Modo de Preparo</label>
                            <div id="preparo-list" style="display:grid; gap:8px;">
                                <input name="modo_preparo[]" type="text" placeholder="Ex: Misturar os ingredientes" />
                            </div>
                            <div class="row" style="margin-top:8px; gap:8px;">
                                <button type="button" id="add-preparo" style="border:0; background:transparent; color:var(--muted); font-size:12px;">+ adicionar</button>
                                <button type="button" id="remove-preparo" style="border:0; background:transparent; color:var(--muted); font-size:12px;">- remover</button>
                            </div>
                        </div>
                        <div>
                            <label>Nivel de Dificuldade</label>
                            <select name="dificuldade" style="width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:6px;">
                                <option value="facil">Facil</option>
                                <option value="medio">Medio</option>
                                <option value="dificil">Dificil</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary">Enviar Receita</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        (function () {
            var input = document.getElementById('foto_file');
            var preview = document.getElementById('foto_preview');
            if (!input || !preview) {
                return;
            }
            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) {
                    preview.style.display = 'none';
                    preview.removeAttribute('src');
                    return;
                }
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            });
        })();
        (function () {
            function addField(listId, name, placeholder) {
                var list = document.getElementById(listId);
                if (!list) {
                    return;
                }
                var input = document.createElement('input');
                input.name = name;
                input.type = 'text';
                input.placeholder = placeholder;
                list.appendChild(input);
            }
            function removeField(listId) {
                var list = document.getElementById(listId);
                if (!list) {
                    return;
                }
                if (list.children.length > 1) {
                    list.removeChild(list.lastElementChild);
                }
            }
            var addIng = document.getElementById('add-ingrediente');
            var remIng = document.getElementById('remove-ingrediente');
            var addPrep = document.getElementById('add-preparo');
            var remPrep = document.getElementById('remove-preparo');

            if (addIng) {
                addIng.addEventListener('click', function () {
                    addField('ingredientes-list', 'ingredientes[]', 'Ex: 1 colher de sal');
                });
            }
            if (remIng) {
                remIng.addEventListener('click', function () {
                    removeField('ingredientes-list');
                });
            }
            if (addPrep) {
                addPrep.addEventListener('click', function () {
                    addField('preparo-list', 'modo_preparo[]', 'Ex: Levar ao forno por 20 min');
                });
            }
            if (remPrep) {
                remPrep.addEventListener('click', function () {
                    removeField('preparo-list');
                });
            }
        })();
    </script>
@endsection
