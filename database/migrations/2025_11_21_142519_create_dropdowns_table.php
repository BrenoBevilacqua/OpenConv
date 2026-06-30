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
        Schema::create('dropdowns', function (Blueprint $table) {
            $table->id();
            $table->string('list'); // exemplo: 'fontes', 'concedentes', 'parlamentares', 'naturezas'
            $table->string('name'); // o valor do item
            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdowns');
    }
};
