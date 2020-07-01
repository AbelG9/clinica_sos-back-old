<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TriageHistory extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idtriaje_historial';
    protected $table = 'triaje_historial';
}
