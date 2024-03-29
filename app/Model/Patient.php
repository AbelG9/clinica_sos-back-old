<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_paciente';
    protected $table = 'paciente';
    const CREATED_AT = 'pac_fech_register';
    const UPDATED_AT = 'pac_fech_update';
}
