<?php
namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;


class CommonController extends Controller
{
    use ApiResponser;

    public function bcryptGenerator($password)
    {
        return \bcrypt($password);
    }

    public function clearCache()
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('clear-compiled');
        \Illuminate\Support\Facades\Artisan::call('event:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        return '<h1>Cache cleared!</h1>';
    }


    public function test()
    {
        return
            '<h1>Test Successful</h1>'
            ;

    }

    public function testDB()
    {
        try {
            DB::connection()->getPdo();
            echo '<h1>Successfully connected to DB! </h1>';
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
    }


}



