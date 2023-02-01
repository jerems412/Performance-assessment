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
        Schema::create('employe_objectif', function (Blueprint $table) {
            $table->id();
            $table->string('dateDepot');
            $table->float('progression');
            $table->boolean('statut');
            $table -> foreignId('employes_id')->constrained();
            $table -> foreignId('objectifs_id')->constrained();
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
        Schema::dropIfExists('employe_objectif');
    }
};
