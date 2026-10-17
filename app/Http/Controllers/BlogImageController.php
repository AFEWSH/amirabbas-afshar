<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BlogImageController
{
    public function __invoke(BlogPost $post): StreamedResponse
    {
        abort_unless(BlogPost::published()->whereKey($post->getKey())->exists(), 404);
        abort_unless($post->featured_image_path && Storage::disk('blog_images')->exists($post->featured_image_path), 404);

        return Storage::disk('blog_images')->response($post->featured_image_path, null, [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
