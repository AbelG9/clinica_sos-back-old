<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    public $timestamps = true;
    protected $primaryKey = 'id_cita_medica';
    protected $table = 'cita_medica';
    const CREATED_AT = 'cme_fech_register';
    const UPDATED_AT = 'cme_fech_update';
}
