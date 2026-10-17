<?php

namespace App\Http\Controllers;

use App\Models\ResumeFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeDownloadController
{
    public function __invoke(string $language): StreamedResponse
    {
        abort_unless(in_array($language, ['en', 'fa'], true), 404);

        $resume = ResumeFile::where('language', $language)->firstOrFail();
        abort_unless($resume->isAvailable(), 404);

        return Storage::disk('resumes')->download($resume->path, "resume-{$language}.pdf", [
            'Content-Type' => 'application/pdf',
            'Content-Language' => $language,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
