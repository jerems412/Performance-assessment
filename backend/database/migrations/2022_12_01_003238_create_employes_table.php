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
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->boolean('statut');
            $table->string('prenom');
            $table->string('nom');
            $table->string('dateNaissance');
            $table->string('genre');
            $table->string('situationMatrimoniale');
            $table->string('nationalite');
            $table->string('adresse');
            $table->string('ville');
            $table->string('pays');
            $table->string('telPersonnel');
            $table->string('telProfessionnel');
            $table->string('emailPersonnel');
            $table->string('emailProfessionnel');
            $table->text('experience');
            $table->text('formation');
            $table->string('langue');
            $table->string('dateEmbauche');
            $table->string('emploi');
            $table->string('matricule');
            $table->string('role');
            $table->bigInteger('domain_id')->unsigned();
            $table->foreign('domain_id')->references('id')->on('domains');
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
        Schema::dropIfExists('employes');
    }
};
