<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainStatus;

class DomainStatusController extends Controller
{
    public function getStatuses()
    {
        $statuses = DomainStatus::all()->mapWithKeys(function($item) {
            return [
                $item->domain => [
                    'is_up' => (bool) $item->is_up,
                    'updated_at' => $item->updated_at->toDateTimeString(),
                    'last_checked_at' => $item->last_checked_at ? 
                        $item->last_checked_at->toDateTimeString() : null
                ]
            ];
        });

        return response()->json($statuses);
    }
}