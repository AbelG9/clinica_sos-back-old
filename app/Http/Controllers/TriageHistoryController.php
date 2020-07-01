<?php

namespace App\Http\Controllers;

use App\Model\TriageHistory;
use App\Model\Triage;
use Illuminate\Http\Request;

class TriageHistoryController extends Controller
{
    public function saveTriageHistory(Request $request)
    {
        $FullTriage = new TriageHistory;
        $FullTriage->q1 = $request->stateOption['option1'];
        $FullTriage->q2 = $request->stateOption['option2'];
        $FullTriage->q3 = $request->stateOption['option3'];
        $FullTriage->q4 = $request->stateOption['option4'];
        $FullTriage->q5 = $request->stateOption['option5'];
        $FullTriage->q6 = $request->stateOption['option6'];
        $FullTriage->q61 = $request->stateOption['option61'];
        $FullTriage->q7 = $request->stateOption['option7'];
        $FullTriage->q8 = $request->stateOption['option8'];
        $FullTriage->paciente_id_paciente = $request->stateOption['usuario'];
        $FullTriage->save();
    
        $NumTriage = Triage::where('paciente_id_paciente', $request->stateOption['usuario'])->select('idtriaje')->get();

        if(count($NumTriage) > 0) {
            $TopTriage = Triage::find($NumTriage[0]->idtriaje);
            $TopTriage->fech_update='2020-07-30';
            $TopTriage->triaje_historial_idtriaje_historial = $FullTriage->idtriaje_historial;
            $TopTriage->paciente_id_paciente = $request->stateOption['usuario'];
            $TopTriage->save();
        } else {
            $TopTriage = new Triage;
            $TopTriage->fech_update='2020-06-30';
            $TopTriage->triaje_historial_idtriaje_historial = $FullTriage->idtriaje_historial;
            $TopTriage->paciente_id_paciente = $request->stateOption['usuario'];
            $TopTriage->save();
        }

        //return $NumTriage[0]->idtriaje;
        //return count($NumTriage);

        return $FullTriage->idtriaje_historial;
    }

    
}
