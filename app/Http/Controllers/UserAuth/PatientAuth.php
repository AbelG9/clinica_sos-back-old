<?php

namespace App\Http\Controllers\UserAuth;

use App\PatientUser;
use App\Model\Patient;
use App\Model\Pes_tool;
use Illuminate\Http\Request;
use App\Model\Patient_especiality;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientAuth extends Controller
{

    public function checkUser (Request $request) {
        $patient = Patient::where('pac_document', '=', $request->credentials['dni'])
            ->select('id_paciente')
            ->get();

        if(count($patient) > 0) {
            $user = PatientUser::where('paciente_id_paciente', '=', $patient[0]->id_paciente)
                ->select('id')
                ->get();
            if (count($user) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'User patient already exist!'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'id_patient' => $patient[0]->id_paciente,
                    'stepPage' => 3,
                    'message' => 'User patient dont exist!'
                ]);
            }
        } else {
            return response()->json([
                'success' => true,
                'stepPage' => 2,
                'message' => 'patient dont exist!'
            ]);
        }
    }

    public function register(Request $request)
    {
        if ($request->credentials['idpatient'] > 0) {
            $user = new PatientUser;
            $user->email = $request->credentials['email'];
            $user->password = bcrypt($request->credentials['pass']);
            $user->paciente_id_paciente = $request->credentials['idpatient'];
            $user->save();

            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'user' => $user,
                'access_token' => $accessToken
            ]);
        } else {
            $FullPatient = new Patient;
            $FullPatient->pac_document = $request->credentials['dni'];
            $FullPatient->pac_name = $request->credentials['name'];
            $FullPatient->pac_lastname = $request->credentials['lastname'];
            $FullPatient->pac_address = $request->credentials['address'];
            $FullPatient->pac_fech_nac = $request->credentials['fechanac'];
            $FullPatient->pac_sex = $request->credentials['genre'];
            $FullPatient->pac_phone = $request->credentials['phone'];
            $FullPatient->pac_email = $request->credentials['email'];
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

            $user = new PatientUser;
            $user->email = $request->credentials['email'];
            $user->password = bcrypt($request->credentials['pass']);
            $user->paciente_id_paciente = $FullPatient->id_paciente;
            $user->save();

            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'user' => $user,
                'access_token' => $accessToken
            ]);
        }
    }

    public function login(Request $request)
    {
        $user = PatientUser::where("email", $request->credentials['user'])->first();
        if(!isset($user)){
            return response()->json([
                'success' => false,
                'message' => 'Staff Not found'
            ]);
        }

        if (!Hash::check($request->credentials['pass'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ]);
        }

        $tokenResult = $user->createToken('authToken');
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Login successfully'
        ]);
    }

    public function logout()
    {
        if (Auth::guard('patientuser')->user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }
}
