<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'objectifs';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * dateDebut.
     *
     * @var string
     */
    protected $dateDebut = 'dateDebut';

    /**
     * dateFin.
     *
     * @var string
     */
    protected $dateFin = 'dateFin';

    /**
     * titre.
     *
     * @var string
     */
    protected $titre = 'titre';

    /**
     * description.
     *
     * @var string
     */
    protected $description = 'description';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    public function alerteobjectifs()
    {
        return $this->belongsTo(AlerteObjectif::class);
    }

    public function commentaires()
    {
        return $this->belongTo(Commentaire::class);
    }

    public function etapes()
    {
        return $this->belongTo(Etape::class);
    }

    public function employes()
    {
        //return $this->belongsToMany(Employe::class)->using(EmployeObjectif::class);
        return $this->belongToMany(Employe::class);
    }

    public function employes1()
    {
        return $this->hasMany(Employe::class);
    }
}
