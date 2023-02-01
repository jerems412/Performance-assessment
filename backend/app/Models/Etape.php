<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'etapes';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * libelle.
     *
     * @var string
     */
    protected $libelle = 'libelle';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    public function objectifs()
    {
        return $this->HasMany(Objectif::class);
    }

    public function employes()
    {
        return $this->belongsToMany(Employe::class)->using(EmployeEtape::class);
    }
}
