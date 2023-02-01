<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * nomDomain.
     *
     * @var string
     */
    protected $nomDomain = 'nomDomain';

    /**
     * dateAjout.
     *
     * @var string
     */
    protected $dateAjout = 'dateAjout';

    /**
     * nomEntreprise.
     *
     * @var string
     */
    protected $nomEntreprise = 'nomEntreprise';

    /**
     * paysEntreprise.
     *
     * @var string
     */
    protected $paysEntreprise = 'paysEntreprise';

    /**
     * villeEntreprise.
     *
     * @var string
     */
    protected $villeEntreprise = 'villeEntreprise';

    /**
     * adresseEntreprise.
     *
     * @var string
     */
    protected $adresseEntreprise = 'adresseEntreprise';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';

    public function employes()
    {
        return $this->belongsTo(Employe::class);
    }
}
