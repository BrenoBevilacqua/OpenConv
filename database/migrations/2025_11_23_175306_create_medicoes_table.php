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
        Schema::create('medicaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
            $table->decimal('porcentagem_conclusao', 15, 2);
            $table->integer('numero_medicao');
            $table->decimal('valor', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaos');
    }
};
