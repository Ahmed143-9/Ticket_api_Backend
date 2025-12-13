<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Enum\PaginationEnum;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    use ApiResponser;

    public function getAllAuditLog_p(Request $request)
    {
        $auditLogList = $this->getAllAuditLog_filter_process($request);
        $auditLogList = $auditLogList->with('logtype:id,log_type', 'user:id,name,email');

        $data = $auditLogList->paginate(PaginationEnum::$DEFAULT);
        $data = [
            'paginator' => getFormattedPaginatedArray($data),
            'data' => $data->items(),
        ];
        return $this->set_response($data, 200,'success', ['Audit log list']);
    }

    public function getAllAuditLog_filter_process(Request $request)
    {
        $logList = AuditLog::orderByDesc('logtime');

        if (isset($request->user_id)) {
            $logList = $logList->where('user_id', $request->user_id);
        }

        if (isset($request->start_date)) {
            $logList = $logList->where('logtime', '>=', $request->start_date);
        }

        if (isset($request->end_date)) {
            $logList = $logList->where('logtime', '<=', $request->end_date);
        }

        return $logList;
    }


    public function createAuditLog(Request $request)
    {
        $request['request_ip'] = \Request::ip();

        DB::beginTransaction();
        try {
            $data = AuditLog::create($request->all());

            DB::commit();
            return $this->set_response($data,  200,'success', ['Audit log added']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }

}

