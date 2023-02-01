<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeEtape extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employe_etape';

    /**
     * The primary key associated with the table.
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * DateValidation.
     *
     * @var string
     */
    protected $DateValidation = 'DateValidation';

    /**
     * statut.
     *
     * @var boolean
     */
    protected $statut = 'statut';
}
