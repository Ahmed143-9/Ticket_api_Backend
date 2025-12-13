<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Configuration\ShortLink;

class ShortLinkService {

    public static function generate($url)
    {
        do {
            $code = Str::random(6);
        } while (ShortLink::where('code', $code)->exists());
        ShortLink::create([
            'code' => $code,
            'original_url' => $url,
        ]);

        return url("/s/$code"); // Returns a shortened URL
    }

}
