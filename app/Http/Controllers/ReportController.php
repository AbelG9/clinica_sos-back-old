<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{

    public function checkDailyReport(Request $request) {
        $time = Carbon::now();
        // $yesterday = Carbon::yesterday()->toDateString();
        $report = Report::where([
                ['staffuser_id', '=', $request->userId],
                ['created_at', 'like', '%' . $time->toDateString() . '%']
            ])->exists();
        if($report) {
            return response()->json([
                'success' => true,
                'message' => 'Success'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Fail'
            ]);
        }
    }

    public function getAllReports(Request $request) {
        $report = Report::where('staffuser_id', '=', $request->userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $total = count($report);
        for ($i=0; $i < $total; $i++) {
            $pathFile = $report[$i]->file_name;
            $url = url('/')."/public".Storage::url("informes/".$pathFile);
            $report[$i]->file_url = $url;
        }

        return response()->json([
            'success' => true,
            'reports' => $report,
            'message' => 'Success'
        ]);
    }

    public function saveReport(Request $request) {

        $size_in_bytes  = (int) (strlen(rtrim($request->report['file_name'], '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes  / 1024;
        // $size_in_mb    = $size_in_kb / 1024;
        if ($size_in_kb <= 10000) {
            $extension = explode('/', mime_content_type($request->report['file_name']));
            if ($extension[1] === 'pdf' || $extension[0] === 'image') {
                $fileName = time().'.'.$extension[1];
                $format = "data:".$extension[0]."/".$extension[1].";base64,";
                $file = str_replace($format, '', $request->report['file_name']);
                $file = str_replace(" ", "+", $file);
                $file = base64_decode($file);
                Storage::disk('public')->put("informes/${fileName}", $file);

                $exists = Storage::disk('public')->exists("informes/${fileName}");
                if ($exists) {
                    $report = new Report;
                    $report->file_name = $fileName;
                    $report->staffuser_id = $request->report['useId'];
                    $report->save();

                    $url = Storage::url("informes/${fileName}");
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
        // return $request->data['file_name'];
    }
}
