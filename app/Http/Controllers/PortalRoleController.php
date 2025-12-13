<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PortalRoleController extends Controller
{
    public function store(Request $request) {
        DB::insert('INSERT INTO portal_role (role_name) VALUES (?)', [$request->role_name]);
        return response()->json(['message' => 'Role created']);
    }

    public function index() {
        $roles = DB::select('SELECT * FROM portal_role');
        return response()->json($roles);
    }

    public function show($id) {
        $role = DB::select('SELECT * FROM portal_role WHERE id = ?', [$id]);
        return response()->json($role);
    }

    public function update(Request $request, $id) {
        DB::update('UPDATE portal_role SET role_name = ? WHERE id = ?', [$request->role_name, $id]);
        return response()->json(['message' => 'Role updated']);
    }

    public function destroy($id) {
        DB::delete('DELETE FROM portal_role WHERE id = ?', [$id]);
        return response()->json(['message' => 'Role deleted']);
    }
}
