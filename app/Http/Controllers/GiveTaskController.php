<?php

namespace App\Http\Controllers;

use App\StaffUser;
use Carbon\Carbon;
use App\Model\GiveTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            ->orderBy('fechafin', 'ASC')
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
        ->orderBy('created_at', 'DESC')
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
            ->orderBy('fecha_entrega', 'ASC')
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
            ->orderBy('fecha_entrega', 'DESC')
            ->get();
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function getTask (Request $request) {
        $task = GiveTask::where('id', '=', $request->id)
            ->select('id', 'asunto', 'detalle', 'created_at', 'fechafin', 'horafin', 'estado', 'fecha_entrega', 'trabajo', 'trabajo_file', 'comentario')
            ->first();
        $pathFile = $task->trabajo_file;
        $url = url('/')."/public".Storage::url("trabajos/".$pathFile);
        $task->file_url = $url;
        return response()->json([
            'success' => true,
            'task' => $task,
            'message' => 'Success'
        ]);
    }

    public function completeTask (Request $request) {
        $size_in_bytes  = (int) (strlen(rtrim($request->data['file_name'], '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes  / 1024;
        // $size_in_mb    = $size_in_kb / 1024;

        if ($size_in_kb <= 10000) {
            $extension = explode('/', mime_content_type($request->data['file_name']));
            if ($extension[1] === 'pdf' || $extension[0] === 'image') {
                $fileName = time().'.'.$extension[1];
                $format = "data:".$extension[0]."/".$extension[1].";base64,";
                $file = str_replace($format, '', $request->data['file_name']);
                $file = str_replace(" ", "+", $file);
                $file = base64_decode($file);
                Storage::disk('public')->put("trabajos/${fileName}", $file);

                $exists = Storage::disk('public')->exists("trabajos/${fileName}");
                if ($exists) {
                    $task = GiveTask::find($request->id);
                    $task->trabajo = $request->data['enlace'];
                    $task->trabajo_file = $fileName;
                    $task->comentario = $request->data['comentario'];
                    $task->fecha_entrega = Carbon::now()->format('Y-m-d H:m:s');
                    $task->estado = 'COMPLETADO';
                    $task->save();
                    $url = Storage::url("trabajos/${fileName}");
                    // $types = $this->mimeTypesAll();
                    return response()->json([
                        'success' => true,
                        'path' => $url,
                        'message' => 'Uploaded successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'path' => $fileName,
                        'message' => 'Failed to upload file'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File not supported!'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File excced the limit!'
            ]);
        }
    }

    private function mimeTypesAll () {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        return $mime_types;
    }
}
