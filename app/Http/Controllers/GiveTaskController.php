<?php

namespace App\Http\Controllers;

use App\StaffUser;
use App\Model\GiveTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiveTaskController extends Controller
{
    public function addTask (Request $request) {
        $task = new GiveTask;
        $task->idUser = $request->data['idUser'];
        $task->asunto = $request->data['asunto'];
        $task->detalle = $request->value;
        $task->fechafin = $request->data['fechafin'];
        $task->horafin = $request->data['horafin'];
        $task->idTrabajador = $request->data['idTrabajador'];
        $task->estado = 'PENDIENTE';
        $task->save();
        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function getUserTask (Request $request) {
        $user = StaffUser::where("username", '!=',$request->username)
            ->join('role_user', 'role_user.id', '=', 'staffuser.role_user_id')
            // ->leftJoin('givetask', 'staffuser.id', '=', 'givetask.idTrabajador')
            ->select('staffuser.id', 'staffuser.full_name', 'staffuser.phone', 'role_user.name as role')
            // ->select(DB::raw('count(*) as user_count, full_name'))
            ->get();

        return response()->json([
            'success' => true,
            'users' => $user,
            'message' => 'Success'
        ]);
    }
}
