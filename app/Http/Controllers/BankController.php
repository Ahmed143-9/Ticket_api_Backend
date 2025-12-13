<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    // Create
    public function store(Request $request) {
        DB::insert('INSERT INTO bank (bank_name) VALUES (?)', [$request->bank_name]);
        return response()->json(['message' => 'Bank created successfully']);
    }

    // Read all
    public function index()
    {
    $banks = DB::select('
        SELECT
            b.id,
            b.bank_name,
            GROUP_CONCAT(ba.branch_name SEPARATOR ", ") AS branch_names
        FROM bank b
        LEFT JOIN bank_account ba ON b.id = ba.bank_id
        GROUP BY b.id, b.bank_name
        ORDER BY b.id ASC
    ');

    return response()->json($banks);
    }

    // Read single
    public function show($id) {
        $bank = DB::select('SELECT * FROM bank WHERE id = ?', [$id]);
        return response()->json($bank);
    }

    // Update
    public function update(Request $request, $id) {
        DB::update('UPDATE bank SET bank_name = ? WHERE id = ?', [$request->bank_name, $id]);
        return response()->json(['message' => 'Bank updated successfully']);
    }

    // Delete
    public function destroy($id) {
        DB::delete('DELETE FROM bank WHERE id = ?', [$id]);
        return response()->json(['message' => 'Bank deleted successfully']);
    }
}
