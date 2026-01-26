<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao');
            $table->text('ingredientes');
            $table->text('modo_preparo');
            // Dificuldade 
            $table->enum('dificuldade', ['facil', 'medio', 'dificil']);
            // Foto da receita
            $table->string('foto')->nullable();
            // Estado moderacao
            $table->enum('estado', ['pendente', 'aprovado', 'rejeitado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
