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
        Schema::create('termos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
            $table->integer('numero_termo');
            $table->string('aditivo');
            $table->decimal('termo_valor', 15, 2)->default(0)->nullable();
            $table->date('termo_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termos');
    }
};
