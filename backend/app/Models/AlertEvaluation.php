<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertEvaluation extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_evaluation';

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

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
