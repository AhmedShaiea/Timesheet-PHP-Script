<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet_type_user extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'timesheet_type_user';

    //const CREATED_AT = 'creation_date';
    //const UPDATED_AT = 'last_update';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
}
