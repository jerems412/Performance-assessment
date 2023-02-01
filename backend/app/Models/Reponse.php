<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reponses';

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
     * note.
     *
     * @var integer
     */
    protected $note = 'note';

    /**
     * datePost.
     *
     * @var string
     */
    protected $datePost = 'datePost';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    /**
     * participant_id.
     *
     * @var integer
     */
    protected $participant_id = 'participant_id';

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    public function questions()
    {
        return $this->hasMany(Critere::class);
    }
}
