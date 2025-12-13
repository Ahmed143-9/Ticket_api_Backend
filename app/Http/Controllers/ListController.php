<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PresetDB;
use App\Models\PresetFile;
use App\Models\DBConditions;
use App\Models\DBServerList;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\DBMaskingTypes;
use App\Models\FileServerList;
use App\Models\PresetFilePath;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Services\DBServerConnectionService;

class ListController extends Controller
{
    use ApiResponser;



    public function getAllFileServerList(Request $request)
    {
        $data = FileServerList::select('id', 'server_name', 'server_ip')->get();
        return $this->set_response(['data' => $data], 200,'success', ['Server list']);
    }


    public function getAllPresetFileCU(Request $request)
    {
        $data = DB::table('userwise_file_access as uw_f_a')
                    ->leftJoin('preset_file as p_f', 'uw_f_a.preset_file_id', 'p_f.id')
                    ->select('p_f.id', 'p_f.preset_name')
                    ->distinct('p_f.id')
                    ->where('uw_f_a.user_id', Auth::user()->id)
                    ->where('uw_f_a.deleted_at', null)
                    ->where('p_f.id', '!=', null)
                    ->get();

        return $this->set_response(['data' => $data], 200,'success', ['Access preset list']);
    }

    public function getAllServerList_b_on_preset_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'preset_file_id' => 'required|numeric|exists:preset_file,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $data = DB::table('preset_file_path as p_f_p')
                    ->leftJoin('file_server_list as f_s_l', 'p_f_p.server_id', 'f_s_l.id')
                    ->select('f_s_l.id', 'f_s_l.server_name', 'f_s_l.server_ip')
                    ->distinct('f_s_l.id')
                    ->where('p_f_p.preset_file_id', $request->preset_file_id)
                    ->where('p_f_p.deleted_at', null)
                    ->get();

        return $this->set_response(['data' => $data], 200,'success', ['Server list']);
    }

    public function getAllPresetFilePath_b_on_s_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'server_id' => 'required|numeric|exists:file_server_list,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $data = DB::table('preset_file_path')
                    ->select('id', 'file_path')
                    ->distinct('file_path')
                    ->where('server_id', $request->server_id)
                    ->where('deleted_at', null)
                    ->get();

        return $this->set_response(['data' => $data], 200,'success', ['Preset File Path List']);
    }

    public function getAllPresetFile(Request $request)
    {
        $data = PresetFile::select('id', 'preset_name')->get();
        return $this->set_response(['data' => $data], 200,'success', ['Preset file list']);
    }

    public function getAllUserList(Request $request)
    {
        $data = User::select('id', 'name', 'email')->get();
        return $this->set_response(['data' => $data], 200,'success', ['User list']);
    }

    public function getAllPresetFilePathList(Request $request)
    {
        $presetFilePathList = PresetFilePath::with('preset_file:id,preset_name', 'file_server:id,server_name,server_ip')->select('id', 'preset_file_id', 'file_path', 'server_id')->orderBy('preset_file_id');

        if (isset($request->preset_file_ids)) {
            $presetFilePathList = $presetFilePathList->whereIn('preset_file_id', $request->preset_file_ids);
        }

        $data = $presetFilePathList->get();

        return $this->set_response(['data' => $data], 200,'success', ['Preset file path list']);
    }

    public function getAllDBServerList(Request $request)
    {
        $data = DBServerList::select('id', 'server_name', 'server_ip', 'db', 'status')->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB server list']);
    }

    // active preset db & db
    public function getAllDBServerList_cu(Request $request)
    {

        $data = DB::table('preset_db_access_details as p_db_acd')
                ->leftJoin('preset_db as p_db', 'p_db_acd.preset_db_id', 'p_db.id')
                ->leftJoin('userwise_db_access as uw_db_a', 'p_db_acd.preset_db_id', '=', 'uw_db_a.preset_db_id')
                ->leftJoin('db_server_list as db_s_l', 'p_db_acd.db_server_id', '=', 'db_s_l.id')
                ->select('p_db_acd.db_server_id as id', 'db_s_l.server_name', 'db_s_l.server_ip', 'db_s_l.port', 'db_s_l.db')
                ->distinct('p_db_acd.db_server_id')
                ->where('uw_db_a.user_id', Auth::user()->id)
                ->where('p_db_acd.deleted_at', null)
                ->where('uw_db_a.deleted_at', null)
                ->where('p_db.status', 1)
                ->where('db_s_l.status', 1)
                ->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB server list']);
    }

    public function getAllDBServerList_b_on_p_db_id(Request $request)
    {
        $data = DB::table('preset_db_access_details as p_db_acd')
                ->leftJoin('preset_db as p_db', 'p_db_acd.preset_db_id', 'p_db.id')
                ->leftJoin('db_server_list as db_s_l', 'p_db_acd.db_server_id', '=', 'db_s_l.id')
                ->where('p_db_acd.preset_db_id', $request->preset_db_id)
                ->select('p_db_acd.db_server_id as id', 'db_s_l.server_name', 'db_s_l.server_ip', 'db_s_l.port', 'db_s_l.db')
                ->distinct('p_db_acd.db_server_id')
                ->where('p_db_acd.deleted_at', null)
                ->where('p_db.status', 1)
                ->where('db_s_l.status', 1)
                ->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB server list']);
    }

    // active preset db & db
    public function getAllTables_b_on_db_s_id_cu(Request $request)
    {
        $data = DB::table('preset_db_access_details as p_db_acd')
                ->leftJoin('preset_db as p_db', 'p_db_acd.preset_db_id', 'p_db.id')
                ->leftJoin('userwise_db_access as uw_db_a', 'p_db_acd.preset_db_id', '=', 'uw_db_a.preset_db_id')
                ->leftJoin('db_server_list as db_s_l', 'p_db_acd.db_server_id', '=', 'db_s_l.id')
                ->where('p_db_acd.db_server_id', $request->db_server_id)
                ->select('p_db_acd.table_name')
                ->distinct('p_db_acd.table_name')
                ->orderBy('p_db_acd.table_name')
                ->where('uw_db_a.user_id', Auth::user()->id)
                ->where('p_db_acd.deleted_at', null)
                ->where('uw_db_a.deleted_at', null)
                ->where('p_db.status', 1)
                ->where('db_s_l.status', 1)
                ->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB tables']);
    }

    // active preset db & db
    public function getAllColumns_b_on_db_s_id_t_n(Request $request)
    {
        $data = DB::table('preset_db_access_details as p_db_acd')
                ->leftJoin('preset_db as p_db', 'p_db_acd.preset_db_id', 'p_db.id')
                ->leftJoin('db_server_list as db_s_l', 'p_db_acd.db_server_id', '=', 'db_s_l.id')
                ->where('p_db_acd.db_server_id', $request->db_server_id)
                ->where('p_db_acd.table_name', $request->table_name)
                ->select('p_db_acd.column_name')
                ->distinct('p_db_acd.column_name')
                // ->orderBy('p_db_acd.column_name')
                ->where('p_db_acd.deleted_at', null)
                ->where('p_db.status', 1)
                ->where('db_s_l.status', 1)
                ->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB columns of a single table']);
    }



    public function getAllPresetDBList(Request $request)
    {
        $data = PresetDB::select('id', 'preset_name')->get();

        return $this->set_response(['data' => $data], 200,'success', ['Preset DB list']);
    }

    public function getAllPresetDBList_cu(Request $request)
    {
        $data = DB::table('userwise_db_access as uw_db_a')
                ->leftJoin('preset_db as p_db', 'uw_db_a.preset_db_id', 'p_db.id')
                ->select('uw_db_a.preset_db_id as id', 'p_db.preset_name')
                ->where('uw_db_a.user_id', Auth::user()->id)
                ->where('p_db.status', 1)
                ->distinct('uw_db_a.preset_db_id')
                ->get();

        return $this->set_response(['data' => $data], 200,'success', ['Current User Preset DB list']);
    }


    public function getDBDetails(Request $request, DBServerConnectionService $dbsconnection)
    {
        $validator = Validator::make($request->all(), [
            'db_server_id' => 'required|numeric|exists:db_server_list,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $params = $dbsconnection->processDBConnectionParams_b_on_db_server_id($request->db_server_id);
        $connection = $dbsconnection->setDBConnection($params);

        $table_names = $connection->table('information_schema.tables')->select('TABLE_NAME')->where('table_schema', $params->database)->get();
        $data_details = [];
        foreach ($table_names as $item)
        {
            $data_details[] = [
                'table_name' => $item->TABLE_NAME,
                'columns' => $connection->table('information_schema.columns')->where('table_schema', $params->database)
                                ->where('TABLE_NAME', $item->TABLE_NAME)
                                ->select('COLUMN_NAME as column_name', 'DATA_TYPE as data_type', 'CHARACTER_MAXIMUM_LENGTH as data_type_length', DB::raw(($request->selected ?? 0).' as selected'))
                                ->orderBy('ORDINAL_POSITION')
                                ->get(),
            ];
        }

        return $this->set_response([
            // 'params' => $params,
            'data' => $data_details
        ], 200,'success', ['DB Details']);
    }


    public function getDBConditions(Request $request)
    {
        $data = DBConditions::select('id', 'condition', 'condition_key', 'rank', 'status')->orderBy('rank');
        if (isset($request->status)) {
            $data = $data->where('status', $request->status);
        }
        $data = $data->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB conditions']);
    }

    public function getDBMaskingTypes(Request $request)
    {
        $data = DBMaskingTypes::select('id', 'masking_type', 'masking_type_key', 'rank', 'status')->orderBy('rank');
        if (isset($request->status)) {
            $data = $data->where('status', $request->status);
        }
        $data = $data->get();

        return $this->set_response(['data' => $data], 200,'success', ['DB Masking Types List']);
    }


}

