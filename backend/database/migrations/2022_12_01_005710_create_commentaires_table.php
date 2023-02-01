<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('libelle');
            $table->string('datePost');
            $table->boolean('statut');
            $table->bigInteger('employe_id')->unsigned();
            $table->foreign('employe_id')->references('id')->on('employes');
            $table->bigInteger('objectif_id')->unsigned();
            $table->foreign('objectif_id')->references('id')->on('objectifs');
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
        Schema::dropIfExists('commentaires');
    }
};
