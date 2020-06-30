<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Patient_especiality extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_paciente_especialidad';
    protected $table = 'paciente_especialidad';
    const CREATED_AT = 'pac_fech_register';
    const UPDATED_AT = 'pac_fech_update';
}
