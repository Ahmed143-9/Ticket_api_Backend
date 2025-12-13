<?php
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Configuration\FileController;

// =======super admin==============
// =======super admin==============

Route::group([
    'prefix' => 'v1'

], function () {
    // users
    Route::post('getAllUsers_p', [UsersController::class, 'getAllUsers_p']);
    Route::post('getAllUsers', [UsersController::class, 'getAllUsers']);
    Route::post('getUser', [UsersController::class, 'getUser']);
    // ->middleware('CheckPermission:user data')
    Route::post('createUser', [UsersController::class, 'createUser']);
    Route::post('updateUser', [UsersController::class, 'updateUser']);

});


// Roles routes
Route::group(['prefix' => 'v1/role', 'middleware' => 'auth:api'],function(){
    Route::post('getAllRoles', [RoleController::class, 'getAllRoles'])->middleware('CheckPermission:role list');
    Route::post('getAllRoles_p', [RoleController::class, 'getAllRoles_p'])->middleware('CheckPermission:role list');
    Route::post('getRole', [RoleController::class, 'getRole'])->middleware('CheckPermission:role list');
    Route::post('createRole', [RoleController::class, 'createRole'])->middleware('CheckPermission:role create');
    Route::post('updateRole', [RoleController::class, 'updateRole'])->middleware('CheckPermission:role update');
    Route::post('deleteRole', [RoleController::class, 'deleteRole'])->middleware('CheckPermission:role delete');
});

//  Permission routes
Route::group(['prefix' => 'v1/permission', 'middleware'=>'auth:api'],function (){
    Route::post('getAllpermissions', [PermissionController::class, 'getAllpermissions'])->middleware('CheckPermission:permission list');
    Route::post('getAllPermissions_p', [PermissionController::class, 'getAllPermissions_p'])->middleware('CheckPermission:permission list');
    Route::post('getPermission', [PermissionController::class, 'getPermission'])->middleware('CheckPermission:permission data');
    Route::post('createPermission', [PermissionController::class, 'createPermission'])->middleware('CheckPermission:permission create');
    Route::post('updatePermission', [PermissionController::class, 'updatePermission'])->middleware('CheckPermission:permission update');
    Route::post('deletePermission', [PermissionController::class, 'deletePermission'])->middleware('CheckPermission:permission delete');
});

//  Permission Module routes
Route::group(['prefix' => 'v1/module', 'middleware'=>'auth:api'],function (){
    Route::post('getAllModules', [ModuleController::class, 'getAllModules']);
});

// =======super admin==============
// =======super admin==============

Route::group(['prefix' => 'v1/general'], function () {
    Route::post('/file/file-upload', [FileController::class, 'fileUpload']);
});


