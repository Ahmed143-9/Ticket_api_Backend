<?php

namespace App\Http\Controllers;

use App\Traits\Queries;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\PermissionModules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    use ApiResponser;
    use Queries;

    public function getAllModules(Request $request)
    {
        $modules = PermissionModules::with('permissions:id,name,module_id')
                    ->select('id', 'name')
                    ->orderBy('name','asc')
                    ->get();
        return $this->set_response(['modulelist' => $modules], 200,'success', ['Module list']);
    }


}
