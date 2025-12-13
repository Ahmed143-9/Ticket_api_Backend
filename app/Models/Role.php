<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model {
    use SoftDeletes;

    protected $table = 'roles';
    protected $primaryKey  = 'id';
    protected $dates = [ 'deleted_at' ];

    protected $hidden = [
        'pivot'
    ];

    public function users() {
        return $this->belongsToMany( User::class, 'model_has_roles', 'role_id',  'model_id' )
        ->wherePivotNull( 'deleted_at' );
    }

    public function permissions() {
        return $this->belongsToMany( Permission::class, 'role_has_permissions', 'role_id',  'permission_id' )
        ->wherePivotNull( 'deleted_at' );
    }

    public function getPermissionsListAttribute() {
        $permissions = $this->permissions()->pluck( 'name' )->toArray();
        return $permissions;
    }

}
