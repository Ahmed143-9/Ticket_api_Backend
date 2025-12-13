<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WintextInvoiceSyncSupportData extends Command {
    protected $signature = 'sync:wintext-invoice-support-data';
    protected $description = 'Fetch and sync support data from sender backend to local database';

    public function handle(): void {
        $baseUrl = rtrim( env( 'SENDER_API_URL', 'http://localhost:82/api' ), '/' );
        $url = "{$baseUrl}/support-data";
        $apiKey = env( 'SENDER_API_KEY', 'asad1947!52!71!24' );

        Log::info( '⏳ Starting hourly support data sync...' );

        try {
            $response = Http::withHeaders( [
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
            ] )->timeout( 60 )->post( $url );

            if ( !$response->successful() ) {
                Log::error( '❌ Failed to fetch support data', [ 'status' => $response->status() ] );
                return;
            }


            $supportData = $response->json( 'data.support', [] );

            foreach ( $supportData as $client ) {
                DB::table( 'wintext_support_data' )->updateOrInsert(
                    [ 'client_id' => $client[ 'client_id' ] ],
                    [
                        // 'sender_support_id' => $client[ 'id' ] ?? null,  // ✅ add this line
                        'client_name'    => $client[ 'client_name' ] ?? null,
                        'client_address' => $client[ 'client_address' ] ?? null,
                        'm_rate_org'     => $client[ 'm_rate_org' ] ?? 0,
                        'nm_rate_view'   => $client[ 'nm_rate_view' ] ?? 0,
                        'kam'            => $client[ 'kam' ] ?? null,
                        'lead_by'        => $client[ 'lead_by' ] ?? null,
                        'client_email'   => $client[ 'client_poc_email' ] ?? null,
                    ]
                );
            }

            Log::info( '✅ Support data sync completed successfully.', [
                'total_synced' => count( $supportData ),

            ] );

        } catch ( \Throwable $e ) {
            Log::error( '⚠️ Support data sync failed: '.$e->getMessage() );
        }
    }
}

// php artisan sync:wintext-invoice-support-data
