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
        Schema::create('employe_evaluation', function (Blueprint $table) {
            $table->id();
            $table->string('dateParticipation');
            $table->float('bilan');
            $table->boolean('statut');
            $table -> foreignId('employes_id')->constrained();
            $table -> foreignId('evaluations_id')->constrained();
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
        Schema::dropIfExists('employe_evaluation');
    }
};
