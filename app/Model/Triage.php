<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Triage extends Model
{
    public $timestamps = true;
    protected $primaryKey = 'idtriaje';
    protected $table = 'triaje';
    const CREATED_AT = 'fech_create';
    const UPDATED_AT = 'fech_update';
}
