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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
            $table->string('numero_contrato');
            $table->text('objeto');
            $table->string('empresa_contratada');
            $table->decimal('valor', 15, 2);
            $table->date('data_assinatura');
            $table->date('validade_inicio');
            $table->date('validade_fim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
