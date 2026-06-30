
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('acompanhamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['em_execucao', 'finalizado', 'cancelado']);
            $table->integer('porcentagem_conclusao');
            $table->boolean('monitorado')->default(false);
            $table->decimal('valor_liberado', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('acompanhamentos');
    }
    };