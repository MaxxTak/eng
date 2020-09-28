<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('tipo');
            $table->string('nome');
            $table->string('razao_social')->nullable();
            $table->string('cnpjcpf');
            $table->string('rg')->nullable();
            $table->string('telefone1')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('telefone3')->nullable();
            $table->string('email')->nullable();
            $table->string('cargo')->nullable();
            $table->string('setor')->nullable();

            $table->integer('endereco_id')->unsigned()->nullable();
            //$table->foreign('endereco_id')->references('id')->on('enderecos');

            $table->integer('user_id')->unsigned()->nullable();
          //  $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('pessoas');
    }
}
