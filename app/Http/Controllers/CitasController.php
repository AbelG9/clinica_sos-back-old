<?php

namespace App\Http\Controllers;

use App\Model\Citas;
use App\Model\Patient;
use App\Model\Patient_especiality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitasController extends Controller
{
    public function getCitas(Request $request)
    {
        $cita = Citas::where('id_usuario',$request->usercita)->select('id_cita_medica as id','cme_fech_inicial as start','cme_fech_final as end','cme_titulo as title')->get();
        return $cita;
    }

    public function getlastcita(Request $request)
    {
        $citauser = Citas::where('id_paciente', '=',$request->dataStorage)
        ->where('cme_estado', '=', 'abierto')
        ->orderBy('cme_fech_inicial')
        ->select('id_cita_medica','cme_fech_inicial','cme_titulo')
        ->get();
        return $citauser;
    }

    public function getCitasByPatient(Request $request)
    {
        $citauser = Citas::where('id_paciente', '=',$request->dataStorage)
        ->orderBy('cme_fech_inicial')
        ->select('id_cita_medica','cme_fech_inicial','cme_titulo','cme_estado')
        ->get();
        return $citauser;
    }

    public function saveCitaOnline(Request $request)
    {

        $pacienteESP = Patient_especiality::where('id_paciente', '=', $request->datoscita['paciente_id_paciente'])
            ->select('id_paciente_especialidad')
            ->get();
        $paciente = Patient::where('id_paciente', '=', $request->datoscita['paciente_id_paciente'])
            ->select('pac_name', 'pac_lastname')
            ->get();

        if (count($pacienteESP) > 0 && count($paciente) > 0) {
            $CitaOnline = new Citas;
            $CitaOnline->id_paciente = $request->datoscita['paciente_id_paciente'];
            $CitaOnline->id_usuario = 19;
            $CitaOnline->cme_paciente_fullname = $paciente[0]->pac_name." ".$paciente[0]->pac_lastname;
            $CitaOnline->cme_color = "#d94848";
            $CitaOnline->cme_fech_inicial = $request->datoscita['hora_inicial'];
            $CitaOnline->cme_fech_final = $request->datoscita['hora_fin'];
            // $CitaOnline->cme_obs="";
            $CitaOnline->cme_titulo = "WEB - ".$request->datoscita['motivo'];
            $CitaOnline->id_especialidad = 1;
            $CitaOnline->cme_attention_pes= $pacienteESP[0]->id_paciente_especialidad;
            $CitaOnline->cme_us_responsable="10";
            // $CitaOnline->cme_tratamiento="";
            // $CitaOnline->cme_trat_descripcion="";
            $CitaOnline->cme_estado = "abierto";
            $CitaOnline->cme_esp_ref = "Dr. Prueba";
            $CitaOnline->cme_manual = "1";
            $CitaOnline->cme_agenda_cita = "";
            $CitaOnline->save();

            return $CitaOnline->id_cita_medica;
        }
    }
}
