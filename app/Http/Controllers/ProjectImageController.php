<?php

namespace App\Http\Controllers;

use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectImageController
{
    public function __invoke(ProjectImage $image): StreamedResponse
    {
        abort_unless($image->project()->published()->exists(), 404);
        abort_unless(Storage::disk('project_images')->exists($image->path), 404);

        return Storage::disk('project_images')->response($image->path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
