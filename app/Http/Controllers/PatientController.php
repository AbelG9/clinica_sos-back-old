<?php

namespace App\Http\Controllers;

use App\Model\Patient;
use App\Model\Pes_tool;
use Illuminate\Http\Request;
use App\Model\Patient_especiality;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilterPatient(Request $request)
    {
        $search='%'.$request->search.'%';
        $patient = Patient::leftJoin('triaje', 'paciente_id_paciente', '=','paciente.id_paciente')
            ->select('paciente.*' ,'triaje.fech_update')
            ->where('paciente.pac_name', 'like', $search)
            ->orderBy('id_paciente', 'desc')
            ->paginate(10);
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

    public function getdataUserPatient(Request $request){
        $datapatient = Patient::where('id_paciente', '=', $request->datospaciente['id_paciente'])
            ->select('id_paciente', 'pac_document', 'pac_name', 'pac_lastname', 'pac_address', 'pac_fech_nac', 'pac_sex', 'pac_phone', 'pac_email')
            ->get();
        return response()->json([
            'success' => true,
            'datapatient' => $datapatient,
            'message' => 'Proccess successfully'
        ]);
    }

    public function updateUserPatient(Request $request) {
        $patient = Patient::find($request->datospaciente['id_paciente']);
        $patient->pac_name = $request->datospaciente['pac_name'];
        $patient->pac_lastname = $request->datospaciente['pac_lastname'];
        $patient->pac_address = $request->datospaciente['pac_address'];
        $patient->pac_phone = $request->datospaciente['pac_phone'];
        $patient->save();
        return response()->json([
            'success' => true,
            'patient' => $patient->pac_name,
            'message' => 'Proccess successfully'
        ]);
    }
}
