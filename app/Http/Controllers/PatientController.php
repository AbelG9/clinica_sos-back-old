<?php

namespace App\Http\Controllers;

use App\Model\Patient;
use App\Model\Patient_especiality;
use App\Model\Pes_tool;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patient = Patient::paginate(10);
        return $patient;
    }

    public function savePatient(Request $request)
    {
        $FullPatient = new Patient;
        $FullPatient->pac_document = $request->params['dnipac'];
        $FullPatient->pac_name = $request->params['nompac'];
        $FullPatient->pac_lastname = $request->params['apelpac'];
        $FullPatient->pac_address = $request->params['dirpac'];
        $FullPatient->pac_fech_nac = $request->params['fnpac'];
        $FullPatient->pac_sex = $request->params['sexpac'];
        $FullPatient->pac_phone = $request->params['telpac'];
        $FullPatient->pac_email = $request->params['mailpac'];
        $FullPatient->pac_estado = 1;
        $FullPatient->save();

        $fichaPatient = new Patient_especiality;
        $fichaPatient->id_paciente = $FullPatient->id_paciente;
        $fichaPatient->id_especialidad = 1;
        $fichaPatient->pes_estado = 'abierto';
        $fichaPatient->save();

        $pesOdontologia = new Pes_tool;
        $pesOdontologia->odto_odon_estado = 'abierto';
        $pesOdontologia->odto_trat_estado = 'abierto';
        $pesOdontologia->odto_hmd_estado = 'abierto';
        $pesOdontologia->id_paciente_especialidad = $fichaPatient->id_paciente_especialidad;
        $pesOdontologia->save();

        return $FullPatient->id_paciente;
    }

    public function getPatient(Request $request) {
        $patient = Patient::where('pac_document',$request->dataDni)->select('id_paciente')->get();
        return $patient;
    }

    public function getDataPatient(Request $request) {
        $datapatient = Patient::where('pac_document',$request->dataDni)->select('pac_name','pac_lastname')->get();
        return $datapatient;
    }
}
