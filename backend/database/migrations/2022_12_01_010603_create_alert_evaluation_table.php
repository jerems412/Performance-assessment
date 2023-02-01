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
        Schema::create('alert_evaluation', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->boolean('statut');
            $table->bigInteger('employe_id')->unsigned();
            $table->foreign('employe_id')->references('id')->on('employes');
            $table->bigInteger('evaluation_id')->unsigned();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');
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
        Schema::dropIfExists('alert_evaluation');
    }
};
