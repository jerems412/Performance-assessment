<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeEvaluation extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employe_evaluation';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * dateParticipation.
     *
     * @var string
     */
    protected $dateParticipation = 'dateParticipation';

    /**
     * bilan.
     *
     * @var float
     */
    protected $bilan = 'bilan';


    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';
}
