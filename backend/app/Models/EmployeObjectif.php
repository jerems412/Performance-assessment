<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeObjectif extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employe_objectif';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * dateDepot.
     *
     * @var string
     */
    protected $dateDepot = 'dateDepot';

    /**
     * progression.
     *
     * @var float
     */
    protected $progression = 'progression';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';
}
