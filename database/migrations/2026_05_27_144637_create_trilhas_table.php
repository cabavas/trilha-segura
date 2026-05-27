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
        Schema::create('trilhas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->dateTime('inicio');                 
    $table->dateTime('fim')->nullable();        
    $table->integer('passos_total')->default(0);
    $table->float('distancia', 2)->nullable(); 
    $table->integer('duracao_segundos')->nullable();
    $table->json('rota')->nullable();           
    $table->boolean('finalizada')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trilhas');
    }
};
