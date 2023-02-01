<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criteres';

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

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function reponses()
    {
        return $this->belongsTo(Reponse::class);
    }
}
