<?php

namespace App\Http\Controllers;

use App\StaffUser;
use Carbon\Carbon;
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
            'task' => $user,
            'message' => 'Success'
        ]);
    }

    public function getTasks (Request $request) {
        $task = GiveTask::where([
                ['idTrabajador', '=', $request->userId],
                ['estado', '=', 'PENDIENTE']
            ])
            ->select('id', 'asunto', 'estado','created_at', 'fechafin', 'horafin')
            ->get();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function getFullTasks () {
        $task = GiveTask::where('estado', '=', 'PENDIENTE')
        ->select('id', 'asunto', 'estado','created_at', 'fechafin', 'horafin')
        ->get();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function getFinishedTasks (Request $request) {
        $task = GiveTask::where([
                ['idTrabajador', '=', $request->userId],
                ['estado', '=', 'COMPLETADO']
            ])
            ->select('id', 'asunto', 'created_at', 'fechafin', 'horafin')
            ->get();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function getFullFinishedTasks () {
        $task = GiveTask::where('estado', '=', 'COMPLETADO')
            ->select('id', 'asunto', 'created_at', 'fechafin', 'horafin')
            ->get();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function getTask (Request $request) {
        $task = GiveTask::where('id', '=', $request->id)
            ->select('id', 'asunto', 'detalle', 'created_at', 'fechafin', 'horafin', 'estado', 'fecha_entrega', 'trabajo')
            ->first();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function completeTask (Request $request) {
        $task = GiveTask::find($request->id);
        $task->trabajo = $request->data['enlace'];
        $task->comentario = $request->data['comentario'];
        $task->fecha_entrega = Carbon::now()->format('Y-m-d H:m:s');
        $task->estado = 'COMPLETADO';
        $task->save();
        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }
}
