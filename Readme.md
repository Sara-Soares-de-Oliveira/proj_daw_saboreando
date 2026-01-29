# Saboreando

Projeto de Desenvolvimento de Aplicacoes Web (DAW) para uma plataforma de receitas.

## Objetivo
A aplicacao permite que utilizadores (explorador e moderador) interajam com receitas, comentarios e denuncias, e recolhe dados de uso para analise.

## Funcionalidades principais
- Autenticacao com perfis (explorador e moderador)
- Criacao e consulta de receitas
- Comentarios e denuncias
- Registo de atividade e analise de uso

## Tecnologias
- Laravel
- MySQL
- Blade

## Instalacao rapida
1) Dependencias:
```
composer install
```

2) Configurar .env:
```
cp .env.example .env
```
Edite o `.env` com as credenciais do MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saboreando
DB_USERNAME=root
DB_PASSWORD=
```

3) Gerar chave:
```
php artisan key:generate
```

4) Migracoes e seed:
```
php artisan migrate:fresh --seed --force
```

## Rotas iniciais
- `/` (login)
- `/entrar`
- `/registar`
- `/nova-palavra-passe`

## Execucao
```
php artisan serve
```
Aceda a `http://127.0.0.1:8000`.

## Estrutura de dados (resumo)
- users (roles: explorador, moderador)
- recipes
- comments
- reports
- user_activities
- recipe_views
- login_sessions

## Nota
Este projeto segue os wireframes e documentos fornecidos na unidade curricular.
