<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiteAssetController
{
    public function __invoke(): StreamedResponse
    {
        $path = SiteSetting::find(1)?->og_image_path;
        abort_unless($path && Storage::disk('site_assets')->exists($path), 404);
        abort_unless(in_array(Storage::disk('site_assets')->mimeType($path), ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return Storage::disk('site_assets')->response($path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
