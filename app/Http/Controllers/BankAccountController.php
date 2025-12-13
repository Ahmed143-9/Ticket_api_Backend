<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BankAccountController extends Controller
{
    public function store(Request $request) {
        DB::insert('INSERT INTO bank_account
        (app_id, bank_id, branch_name, routing_number, is_default, account_name, account_number)
        VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $request->app_id,
            $request->bank_id,
            $request->branch_name,
            $request->routing_number,
            $request->is_default ?? 0,
            $request->account_name,
            $request->account_number
        ]);
        return response()->json(['message' => 'Bank account created']);
    }

    public function index() {
        $accounts = DB::select('SELECT * FROM bank_account');
        return response()->json($accounts);
    }

    public function show($id) {
        $account = DB::select('SELECT * FROM bank_account WHERE id = ?', [$id]);
        return response()->json($account);
    }

    public function update(Request $request, $id) {
        DB::update('UPDATE bank_account SET
            app_id = ?, bank_id = ?, branch_name = ?, routing_number = ?,
            is_default = ?, account_name = ?, account_number = ?
            WHERE id = ?', [
            $request->app_id,
            $request->bank_id,
            $request->branch_name,
            $request->routing_number,
            $request->is_default ?? 0,
            $request->account_name,
            $request->account_number,
            $id
        ]);
        return response()->json(['message' => 'Bank account updated']);
    }

    public function destroy($id) {
        DB::delete('DELETE FROM bank_account WHERE id = ?', [$id]);
        return response()->json(['message' => 'Bank account deleted']);
    }
}
