<?php

namespace App\Models;

use App\Models\Role as Role_c;
use App\Models\PermissionModules;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey  = 'id';
    protected $hidden = [
        'pivot'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role_c::class, 'role_has_permissions',  'permission_id', 'role_id')->wherePivotNull( 'deleted_at' );
    }

    public function module()
    {
        return $this->belongsTo(PermissionModules::class,  'module_id', 'id');
    }


}
