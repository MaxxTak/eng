<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('pessoa_id')->unsigned()->nullable();
        //    $table->foreign('pessoa_id')->references('id')->on('pessoas');

            $table->integer('tipo_obra_id')->unsigned()->nullable();
         //   $table->foreign('tipo_obra_id')->references('id')->on('tipos_obra');

            $table->integer('forma_pagamento_id')->unsigned()->nullable();
         //   $table->foreign('forma_pagamento_id')->references('id')->on('formas_pagamento');

            $table->string('descricao');

            $table->integer('prazo_previsto')->nullable();
            $table->string('tipo_prazo_previsto')->nullable();
            $table->date('inicio');

            $table->string('observacoes_pagamento');

            $table->integer('endereco_id')->unsigned()->nullable();
         //   $table->foreign('endereco_id')->references('id')->on('enderecos');

            $table->integer('cobranca_id')->unsigned()->nullable();
           // $table->foreign('cobranca_id')->references('id')->on('cobrancas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('obras');
    }
}
