<?php

namespace App\Http\Controllers;

use App\Model\Citas;
use Illuminate\Http\Request;

class CitasController extends Controller
{
    public function saveCitaOnline(Request $request)
    {;
        $CitaOnline = new Cita;
        $CitaOnline->id_paciente = 1000;
        $CitaOnline->id_usuario = 33
        $CitaOnline->cme_paciente_fullname = "";
        $CitaOnline->cme_color = "";
        $CitaOnline->cme_fech_inicial = $request->datoscitaSave['fech_inicial'];
        $CitaOnline->cme_fech_final = "";
        $CitaOnline->cme_obs="";
        $CitaOnline->cme_titulo = $request->datoscitaSave['titulo'];
        $CitaOnline->id_especialidad = 1;
        $CitaOnline->cme_attention_pes="";
        $CitaOnline->cme_us_responsable="";
        $CitaOnline->cme_tratamiento="";
        $CitaOnline->cme_trat_descripcion="";
        $CitaOnline->cme_esp_attention_start="";
        $CitaOnline->cme_esp_attention_end="";
        $CitaOnline->cme_estado = 'abierto';
        $CitaOnline->cme_esp_ref = "";
        $CitaOnline->cme_manual = "";
        $CitaOnline->cme_aud_atencioncalf="";
        $CitaOnline->cme_aud_obs="";
        $CitaOnline->cme_aud_user="";
        $CitaOnline->cme_llamado_efectuado="";
        $CitaOnline->save();

        return $CitaOnline->id_cita_medica;
    }
