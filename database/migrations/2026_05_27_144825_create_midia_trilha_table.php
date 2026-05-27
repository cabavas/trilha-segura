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
        Schema::create('midia_trilha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trilha_id')->constrained()->onDelete('cascade');
            $table->string('tipo');
            $table->string('local_path');
            $table->string('remote_url')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('capturado_em');
            $table->boolean('sincronizado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midia_trilha');
    }
};
