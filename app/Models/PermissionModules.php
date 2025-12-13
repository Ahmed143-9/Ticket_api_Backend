<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class PermissionModules extends Model
{
    protected $table = 'permission_modules';
    protected $primaryKey  = 'id';


    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id', 'id')->where('permissions.deleted_at', NULL);
    }

}
