<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogHistoricoTable extends Migration
{
    public function up()
    {
        Schema::create('log_historico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID do usuário que fez a alteração
            $table->string('acao'); // Ação: criação, edição, exclusão, etc.
            $table->string('numero_convenio'); // Número do convênio
            $table->string('ano_convenio'); // Ano do convênio
            $table->timestamp('data_modificacao'); // Data da modificação
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_historico');
    }
}
