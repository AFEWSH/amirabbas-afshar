<?php

namespace App\Providers;

use App\Models\Profile;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Support\PublicUrl;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(5)
            ->by($request->ip() ?: 'unknown')
            ->response(fn () => redirect()->to(PublicUrl::route('home').'#contact')->with('contact_error', __('Please wait before sending another message.'))));

        View::composer('components.layouts.public', function (ViewInstance $view): void {
            if (! ($view->getData()['profile'] ?? null)) {
                $view->with('profile', Profile::find(1));
            }

            $view->with('settings', SiteSetting::find(1));
            $view->with('footerSocialLinks', SocialLink::visible()->availableInPublicLocale('label')->orderBy('sort_order')->orderBy('id')->get());
        });
    }
}
