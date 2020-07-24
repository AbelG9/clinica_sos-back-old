<?php

namespace App\Http\Controllers;

use App\Model\Citas;
use Illuminate\Http\Request;

class CitasController extends Controller
{
    public function getCitas(Request $request)
    {
        $cita = Citas::where('id_usuario',$request->usercita)->select('id_cita_medica as id','cme_fech_inicial as start','cme_fech_final as end','cme_titulo as title')->get();
        return $cita;
    }
    
    public function getlastcita(Request $request)
    {
        $citauser = Citas::where('id_paciente', '=',$request->idpaciente)
        ->where('cme_estado', '=', 'abierto')
        //->addSelect(Citas::raw('MAX(cme_fech_register)'))
        ->select('id_cita_medica')
        ->get();
        return $citauser;
    }

    public function saveCitaOnline(Request $request)
    {
        $CitaOnline = new Citas;
        $CitaOnline->id_paciente = $request->datoscita['idpaciente'];
        $CitaOnline->id_usuario = 33;
        // $CitaOnline->cme_paciente_fullname = "";
        // $CitaOnline->cme_color = "";
        $CitaOnline->cme_fech_inicial = $request->datoscita['hora_inicial'];
        $CitaOnline->cme_fech_final = $request->datoscita['hora_fin'];
        // $CitaOnline->cme_obs="";
        $CitaOnline->cme_titulo = $request->datoscita['motivo'];
        $CitaOnline->id_especialidad = 1;
        // $CitaOnline->cme_attention_pes="";
        // $CitaOnline->cme_us_responsable="";
        // $CitaOnline->cme_tratamiento="";
        // $CitaOnline->cme_trat_descripcion="";
        // $CitaOnline->cme_estado = "abierto";
        // $CitaOnline->cme_esp_ref = "";
        // $CitaOnline->cme_manual = "";
        $CitaOnline->cme_agenda_cita = "";
        $CitaOnline->save();
        
        return $CitaOnline->id_cita_medica;
    }
}