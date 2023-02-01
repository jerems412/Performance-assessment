<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluations';

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
     * type.
     *
     * @var string
     */
    protected $type = 'type';

    /**
     * contexte.
     *
     * @var string
     */
    protected $contexte = 'contexte';

    /**
     * objectif.
     *
     * @var string
     */
    protected $objectif = 'objectif';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    public function alerteevaluations()
    {
        return $this->belongsTo(AlerteEvaluation::class);
    }

    public function employes()
    {
        return $this->belongsToMany(Employe::class);
    }

    public function criteres()
    {
        return $this->belongsTo(Critere::class);
    }

    public function employes1()
    {
        return $this->hasMany(Employe::class);
    }

    
}
