<?php

namespace App\Models;

use App\Models\Application;
use App\Models\Role as Role_c;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, \Spatie\Permission\Traits\HasRoles;

    protected $table = 'users';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'status','department', 'force_password', 'phone', 'joining_date'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token', 'pivot'
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'integer',
    ];

    public function setStatusAttribute( $value ) {
        $this->attributes[ 'status' ] = $value ?? '1';
    }

    public function roles() {
        return $this->belongsToMany( Role_c::class, 'model_has_roles', 'model_id', 'role_id' )->wherePivotNull( 'deleted_at' );
    }

    public function application() {
        return $this->belongsTo( Application::class, 'app_id', 'id' );
    }

}
