<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainStatus;
use Carbon\Carbon;

class DomainStatusController extends Controller
{
    public function getStatuses()
    {
        $statuses = DomainStatus::all()->mapWithKeys(function($item) {
            // Safely handle updated_at whether it's string or Carbon
            $updatedAt = $item->updated_at;
            if (is_string($updatedAt)) {
                $updatedAt = Carbon::parse($updatedAt)->toDateTimeString();
            } else if ($updatedAt instanceof \Carbon\Carbon) {
                $updatedAt = $updatedAt->toDateTimeString();
            }
            
            // Handle last_checked_at if you have this field
            $lastChecked = null;
            if (isset($item->last_checked_at) && $item->last_checked_at) {
                $lastChecked = is_string($item->last_checked_at) 
                    ? Carbon::parse($item->last_checked_at)->toDateTimeString()
                    : $item->last_checked_at->toDateTimeString();
            }
            
            return [
                $item->domain => [
                    'is_up' => (bool) $item->is_up,
                    'updated_at' => $updatedAt,
                    'last_checked_at' => $lastChecked
                ]
            ];
        });

        return response()->json($statuses);
    }
}