<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employes';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    /**
     * nom.
     *
     * @var string
     */
    protected $nom = 'nom';

    /**
     * prenom.
     *
     * @var string
     */
    protected $prenom = 'prenom';

    /**
     * dateNaissance.
     *
     * @var string
     */
    protected $dateNaissance = 'dateNaissance';

    /**
     * genre.
     *
     * @var string
     */
    protected $genre = 'genre';

    /**
     * situationMatrimoniale.
     *
     * @var string
     */
    protected $situationMatrimoniale = 'situationMatrimoniale';

    /**
     * nationalite.
     *
     * @var string
     */
    protected $nationalite = 'nationalite';

    /**
     * adresse.
     *
     * @var string
     */
    protected $adresse = 'adresse';

    /**
     * ville.
     *
     * @var string
     */
    protected $ville = 'ville';

    /**
     * pays.
     *
     * @var string
     */
    protected $pays = 'pays';

    /**
     * telPersonnel.
     *
     * @var string
     */
    protected $telPersonnel = 'telPersonnel';

    /**
     * telProfessionnel.
     *
     * @var string
     */
    protected $telProfessionnel = 'telProfessionnel';

    /**
     * emailPersonnel.
     *
     * @var string
     */
    protected $emailPersonnel = 'emailPersonnel';

    /**
     * emailProfessionnel.
     *
     * @var string
     */
    protected $emailProfessionnel = 'emailProfessionnel';

    /**
     * experience.
     *
     * @var string
     */
    protected $experience = 'experience';

    /**
     * formation.
     *
     * @var string
     */
    protected $formation = 'formation';

    /**
     * langue.
     *
     * @var string
     */
    protected $langue = 'langue';

    /**
     * dateEmbauche.
     *
     * @var string
     */
    protected $dateEmbauche = 'dateEmbauche';

    /**
     * emploi.
     *
     * @var string
     */
    protected $emploi = 'emploi';

    /**
     * matricule.
     *
     * @var string
     */
    protected $matricule = 'matricule';

    /**
     * role.
     *
     * @var string
     */
    protected $role = 'role';


    public function users()
    {
        return $this->morphMany(User::class, 'roleusertable');
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function alerteobjectifs()
    {
        return $this->belongsTo(AlerteObjectif::class);
    }

    public function alertevaluations()
    {
        return $this->belongsTo(AlertEvaluation::class);
    }

    public function commentaires()
    {
        return $this->belongsTo(Commentaire::class);
    }

    public function reponses()
    {
        return $this->belongsTo(Reponse::class);
    }

    public function etapes()
    {
        return $this->belongsToMany(Etape::class);
    }

    public function objectifs()
    {
        //return $this->belongsToMany(Objectif::class);
        return $this->belongToMany(Objectif::class);
    }

    public function evaluations()
    {
        return $this->belongsToMany(Evaluation::class);
    }

    public function objectifs1()
    {
        return $this->belongTo(Objectif::class);
    }

    public function evaluations1()
    {
        return $this->belongTo(Evaluation::class);
    }

}
