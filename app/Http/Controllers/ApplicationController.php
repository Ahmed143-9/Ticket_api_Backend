<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ApplicationController extends Controller
{
    public function store(Request $request) {
        DB::insert('INSERT INTO application (name) VALUES (?)', [
            $request->name

        ]);
        return response()->json(['message' => 'Application created']);
    }

    public function index() {
        $apps = DB::select('SELECT * FROM application');
        return response()->json($apps);
    }

    public function show($id) {
        $app = DB::select('SELECT * FROM application WHERE id = ?', [$id]);
        return response()->json($app);
    }

    public function update(Request $request, $id) {
        DB::update('UPDATE application SET name = ? WHERE id = ?', [
            $request->name,
            $id
        ]);
        return response()->json(['message' => 'Application updated']);
    }

    public function destroy($id) {
        DB::delete('DELETE FROM application WHERE id = ?', [$id]);
        return response()->json(['message' => 'Application deleted']);
    }
}
