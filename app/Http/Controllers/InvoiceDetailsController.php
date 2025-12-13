<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceDetailsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|integer',
            'description' => 'required|string',
            'sms_qty' => 'required|integer',
            'unit_price' => 'required|numeric',
            'total' => 'required|numeric'
        ]);

        DB::table('invoice_details')->insert($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Invoice item added successfully'
        ]);
    }

    public function delete($id)
    {
        DB::table('invoice_details')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Item deleted']);
    }
}
