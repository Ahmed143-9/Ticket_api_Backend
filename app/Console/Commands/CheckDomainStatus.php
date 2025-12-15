<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DomainStatus;

class CheckDomainStatus extends Command
{
    protected $signature = 'domains:check';
    protected $description = 'Check status of all domains/subdomains';

    public function handle()
    {
        \Log::info("Domain status check started at: " . now());
        
        $domains = [
            'https://solquestbd.com',
            'https://wintelbd.com',
            'https://winsourcesbd.com',
            'https://battlechamp.win',
            'https://mobiappsbd.com',
            'https://mobile-masala.com',
            'https://svcwin.com',
            'https://wincommerz.com',
            'https://winconnectbd.com',
            'https://winfinbd.com',
            'https://wimpaybd.com',
            'https://wimpgbd.com',
            'https://wintelxbd.com',
            'https://yogadubbd.com',
            'https://wineds.com',
        ];

        // Use Guzzle or direct curl without process dependency
        $results = $this->checkDomainsWithMultiCurl($domains);

        foreach ($results as $domain => $is_up) {
            DomainStatus::updateOrCreate(
                ['domain' => $domain],
                [
                    'is_up' => $is_up,
                    'last_checked_at' => now()
                ]
            );
        }

        \Log::info("Domain status check completed at: " . now());
        $this->info('Domain status updated successfully.');
    }

    private function checkDomainsWithMultiCurl(array $domains): array
    {
        $results = [];
        $multi_handle = curl_multi_init();
        $curl_handles = [];

        foreach ($domains as $domain) {
            $ch = curl_init($domain);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            curl_multi_add_handle($multi_handle, $ch);
            $curl_handles[$domain] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($multi_handle, $running);
            curl_multi_select($multi_handle);
        } while ($running > 0);

        foreach ($curl_handles as $domain => $ch) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $results[$domain] = ($http_code >= 200 && $http_code < 400);
            curl_multi_remove_handle($multi_handle, $ch);
            curl_close($ch);
        }

        curl_multi_close($multi_handle);
        return $results;
    }
}