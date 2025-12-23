<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RSAController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MFSTypeController;
use App\Http\Controllers\PortalRoleController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\DomainStatusController;


use App\Http\Controllers\ProblemController;
use App\Http\Controllers\FirstFaceAssignmentController;
// use App\Http\Controllers\Dashboard\DashboardController;
// -----------------------
// Banks CRUD
// -----------------------
Route::group(['prefix' => 'banks', 'middleware' => 'auth:api'],function(){
    Route::get('/', [BankController::class, 'index']);      // List all banks
    Route::get('/{id}', [BankController::class, 'show']);  // Show single bank
    Route::post('/', [BankController::class, 'store']);     // Create bank
    Route::put('/{id}', [BankController::class, 'update']); // Update bank
    // Route::delete('/{id}', [BankController::class, 'destroy']); // Delete bank
});


// -----------------------
// Bank Accounts CRUD
// -----------------------
Route::group(['prefix' => 'bank-accounts', 'middleware' => 'auth:api'],function(){
    Route::get('/', [BankAccountController::class, 'index']);
    Route::get('/{id}', [BankAccountController::class, 'show']);
    Route::post('/', [BankAccountController::class, 'store']);
    Route::put('/{id}', [BankAccountController::class, 'update']);
    // Route::delete('/bank-accounts/{id}', [BankAccountController::class, 'destroy']);
});

// -----------------------
// Company Info CRUD
// -----------------------
Route::group(['prefix' => 'companies', 'middleware' => 'auth:api'],function(){
    Route::get('/', [CompanyInfoController::class, 'index']);
    Route::get('/{id}', [CompanyInfoController::class, 'show']);
    Route::post('/', [CompanyInfoController::class, 'store']);
    Route::put('/{id}', [CompanyInfoController::class, 'update']);
    // Route::delete('/{id}', [CompanyInfoController::class, 'destroy']);
});


// -----------------------
// Applications CRUD
// -----------------------
Route::group(['prefix' => 'applications', 'middleware' => 'auth:api'],function(){
    Route::get('/', [ApplicationController::class, 'index']);
    Route::get('/{id}', [ApplicationController::class, 'show']);
    Route::post('/', [ApplicationController::class, 'store']);
    Route::put('/{id}', [ApplicationController::class, 'update']);
    Route::delete('/{id}', [ApplicationController::class, 'destroy']);
});



// -----------------------
// Portal Roles CRUD
// -----------------------
Route::group(['prefix' => 'roles', 'middleware' => 'auth:api'],function(){
    Route::get('/', [PortalRoleController::class, 'index']);
    Route::get('/{id}', [PortalRoleController::class, 'show']);
    Route::post('/', [PortalRoleController::class, 'store']);
    Route::put('/{id}', [PortalRoleController::class, 'update']);
    Route::delete('/{id}', [PortalRoleController::class, 'destroy']);
});



// ========Auth===========
// ========Auth===========
Route::post('v1/test', function(Request $request) {
    return response()->json([
        'message' => 'Test route works!',
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'body' => $request->all()
    ]);
});

Route::group([
    'prefix' => 'v1'
], function(){
    Route::post('login', [AuthController::class, 'login'])
    // ->name('login')->middleware("throttle:30,5")
    ;
    // ->middleware("throttle:3,5")
    Route::post('signup', [AuthController::class, 'signup']);
});

Route::group([
    'prefix' => 'v1',
    'middleware' => 'auth:api',
], function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    // Route::post('changePassword', [AuthController::class, 'changePassword']);
    Route::post('profileUpdate', [AuthController::class, 'profileUpdate']);
});
// ========Auth===========
// ========Auth===========







// =======List Data==============
// =======List Data==============
Route::group(['prefix' => 'v1/list', 'middleware'=>'auth:api'],function (){

    Route::post('getAllUserList', [ListController::class, 'getAllUserList']);
});
// =======List Data==============
// =======List Data==============

// =======Audit Log Data==============
// =======Audit Log Data==============
Route::group(['prefix' => 'v1/audit-log'],function (){
    Route::post('createAuditLog', [AuditLogController::class, 'createAuditLog']);
    Route::group(['middleware'=>'auth:api'],function (){
        Route::post('getAllAuditLog_p', [AuditLogController::class, 'getAllAuditLog_p'])->middleware('CheckPermission:audit log list');
    });
});
// =======Audit Log Data==============
// =======Audit Log Data==============

// common
// common
// Route::get('bcryptGenerator/{password}', [CommonController::class, 'bcryptGenerator']);
Route::post('clear', [CommonController::class, 'clearCache']);
Route::get('test', [CommonController::class, 'test']);
Route::get('testDB', [CommonController::class, 'testDB']);

// Route::post('/test/preg_match', [TestController::class, 'preg_match']);


// =======RSA==============
// =======RSA==============
Route::group(['prefix' => 'v1/rsa', 'middleware'=>'auth:api' ],function (){
    Route::post('encrypt', [RSAController::class, 'encrypt']);
    Route::post('decrypt', [RSAController::class, 'decrypt']);
});
// =======RSA==============
// =======RSA==============




// Dashboard Routes
// Route::group(['prefix' => 'v1/dashboard', 'middleware' => 'auth:api'],function(){
//     Route::get('dashboard-data', [DashboardController::class, 'getDashboardData']);
// });
// Route::get('index/{id}', [UsersController::class, 'index']);


Route::group(['prefix' => 'index', 'middleware' => 'auth:api'],function(){
    Route::get('/{id?}', [UsersController::class, 'index']);
});



// -----------------------
// Invoice Routes
// -----------------------
// Route::group(['prefix' => 'wintext-invoice', 'middleware' => 'auth:api'],function(){
//     Route::get('/list-paginate', [InvoiceController::class, 'listPaginate']);
//     Route::get('/single-data/{id}', [InvoiceController::class, 'singleData']);
//     Route::post('/create', [InvoiceController::class, 'create']);
//     Route::put('/update', [InvoiceController::class, 'update']);

//     Route::get('get-support-data', [InvoiceController::class, 'getSupportData']);
//     Route::get('/filter-data', [InvoiceController::class, 'filterData']);
//     Route::post('/get-sms-quantity', [InvoiceController::class, 'getSmsQuantity']);
//     Route::get('/payment-accounts', [InvoiceController::class, 'getPaymentAccounts']);
// });


// Bank accounts route (ADD THIS - your frontend needs it!)
// Route::get('/bank-accounts', [InvoiceController::class, 'getBankAccounts']);

// Support data route (ADD THIS - your frontend needs it!)
// Route::get('/get-support-data', [InvoiceController::class, 'getsupportdata']);





Route::group(['prefix' => 'payment-method', 'middleware' => 'auth:api'],function(){
    Route::get('/payment-method-types', [PaymentMethodController::class, 'getPaymentMethodTypes']);
});

Route::group(['prefix' => 'mfs-types', 'middleware' => 'auth:api'],function(){
    Route::get('/get-all', [MFSTypeController::class, 'getAll']);
});


// Problem routes
Route::post('/problems/create', [ProblemController::class, 'createProblem']);
Route::post('/problems/update', [ProblemController::class, 'updateProblem']);
Route::post('/problems/get', [ProblemController::class, 'getProblem']);
Route::post('/problems/getAll', [ProblemController::class, 'getAllProblems']);
Route::post('/problems/delete', [ProblemController::class, 'deleteProblem']);
Route::post(
    '/problems/assigned-by-user',
    [ProblemController::class, 'getAssignedProblemsByUser']
);

Route::post('/problems/comment', [ProblemController::class, 'addComment'])->middleware('auth:api');
// ->middleware('auth:sanctum')
;

// First Face Assignment routes
Route::post('/first-face-assignments/create', [FirstFaceAssignmentController::class, 'createAssignment']);
Route::post('/first-face-assignments/update', [FirstFaceAssignmentController::class, 'updateAssignment']);
Route::post('/first-face-assignments/delete', [FirstFaceAssignmentController::class, 'deleteAssignment']);
Route::post('/first-face-assignments/getAll', [FirstFaceAssignmentController::class, 'getAllAssignments']);
Route::post('/first-face-assignments/getActive', [FirstFaceAssignmentController::class, 'getActiveAssignments']);

// Domain Status Management
Route::group(['prefix' => 'domains'], function() {
    Route::get('/status', [DomainStatusController::class, 'getStatuses']);     // Get all domains
    Route::post('/', [DomainStatusController::class, 'store']);                // Add new domain
    Route::put('/{id}', [DomainStatusController::class, 'update']);            // Update domain
    Route::delete('/{id}', [DomainStatusController::class, 'destroy']);        // Delete domain
});
