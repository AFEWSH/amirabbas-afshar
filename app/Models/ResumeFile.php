<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResumeFile extends Model
{
    protected $fillable = ['language', 'path', 'original_name'];

    public function isAvailable(): bool
    {
        $disk = Storage::disk('resumes');

        if (! str_ends_with(strtolower($this->path), '.pdf') || ! $disk->exists($this->path) || $disk->mimeType($this->path) !== 'application/pdf') {
            return false;
        }

        $stream = $disk->readStream($this->path);

        if (! is_resource($stream)) {
            return false;
        }

        try {
            return fread($stream, 5) === '%PDF-';
        } finally {
            fclose($stream);
        }
    }
}
