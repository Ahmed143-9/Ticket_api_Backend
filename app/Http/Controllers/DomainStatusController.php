<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainStatus;
use Carbon\Carbon;

class DomainStatusController extends Controller
{
    // Get all domain statuses
    public function getStatuses()
    {
        $statuses = DomainStatus::all()->mapWithKeys(function($item) {
            $updatedAt = $item->updated_at;
            if (is_string($updatedAt)) {
                $updatedAt = Carbon::parse($updatedAt)->toDateTimeString();
            } else if ($updatedAt instanceof \Carbon\Carbon) {
                $updatedAt = $updatedAt->toDateTimeString();
            }

            $lastChecked = null;
            if (isset($item->last_checked_at) && $item->last_checked_at) {
                $lastChecked = is_string($item->last_checked_at)
                    ? Carbon::parse($item->last_checked_at)->toDateTimeString()
                    : $item->last_checked_at->toDateTimeString();
            }

            return [
                $item->domain => [
                    'id' => $item->id,
                    'is_up' => (bool) $item->is_up,
                    'is_active' => (bool) $item->is_active,
                    'updated_at' => $updatedAt,
                    'last_checked_at' => $lastChecked
                ]
            ];
        });

        return response()->json($statuses);
    }

    // Add a new domain to monitor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|url|unique:domain_statuses,domain',
            'is_active' => 'boolean'
        ]);

        $domain = DomainStatus::create([
            'domain' => $validated['domain'],
            'is_active' => $validated['is_active'] ?? true,
            'is_up' => false,
            'last_checked_at' => null
        ]);

        return response()->json([
            'message' => 'Domain added successfully',
            'data' => $domain
        ], 201);
    }

    // Update domain status or is_active flag
    public function update(Request $request, $id)
    {
        $domain = DomainStatus::findOrFail($id);

        $validated = $request->validate([
            'domain' => 'string|url|unique:domain_statuses,domain,' . $id,
            'is_active' => 'boolean'
        ]);

        $domain->update($validated);

        return response()->json([
            'message' => 'Domain updated successfully',
            'data' => $domain
        ]);
    }

    // Delete a domain from monitoring
    public function destroy($id)
    {
        $domain = DomainStatus::findOrFail($id);
        $domain->delete();

        return response()->json([
            'message' => 'Domain deleted successfully'
        ]);
    }
}
