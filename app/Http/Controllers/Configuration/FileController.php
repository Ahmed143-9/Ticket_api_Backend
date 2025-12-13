<?php

namespace App\Http\Controllers\Configuration;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    use ApiResponser;


    public function fileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:png,jpg,jpeg,jfif,webp,gif,doc,docx,csv,xls,xlsx,pdf|max:5120',
                'file_path' => 'required',
                'file_name' => 'required',
            ],
            [
                'file.max' => 'File size should be max 5 MB'
            ]

        );
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $user = Auth::user();

        try {
            $file_path = $request->file_path;
            $file_name = $request->file_name;
            [$file_path] = getProcessFile($request->file, $file_name, $file_path);

            return $this->set_response(['file_path'=>$file_path],  200,'success', ['File uploaded successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'debug');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }

}
