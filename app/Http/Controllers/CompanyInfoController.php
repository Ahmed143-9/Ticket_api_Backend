<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CompanyInfoController extends Controller
{
    public function store(Request $request) {
        DB::insert('INSERT INTO company_info
            (app_id, address, phone, fax, email, web, is_default)
            VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $request->app_id,
            $request->address,
            $request->phone,
            $request->fax,
            $request->email,
            $request->web,
            $request->is_default ?? 0
        ]);
        return response()->json(['message' => 'Company info created']);
    }

    public function index() {
        $companies = DB::select('SELECT * FROM company_info');
        return response()->json($companies);
    }

    public function show($id) {
        $company = DB::select('SELECT * FROM company_info WHERE id = ?', [$id]);
        return response()->json($company);
    }

    public function update(Request $request, $id) {
        DB::update('UPDATE company_info SET
            app_id = ?, address = ?, phone = ?, fax = ?, email = ?, web = ?, is_default = ?
            WHERE id = ?', [
            $request->app_id,
            $request->address,
            $request->phone,
            $request->fax,
            $request->email,
            $request->web,
            $request->is_default ?? 0,
            $id
        ]);
        return response()->json(['message' => 'Company info updated']);
    }

    public function destroy($id) {
        DB::delete('DELETE FROM company_info WHERE id = ?', [$id]);
        return response()->json(['message' => 'Company info deleted']);
    }
}
