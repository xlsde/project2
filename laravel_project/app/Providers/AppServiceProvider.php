<?php

namespace App\Providers;

use App\Models\Auction;
use App\Policies\AuctionPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Behind the preview ingress the forwarded Host/Proto are not trusted, so Laravel
        // generated links/form actions against an internal cluster host over http://.
        // Pin all generated URLs to the configured APP_URL.
        $appUrl = config('app.url');
        if ($appUrl) {
            URL::forceRootUrl($appUrl);
            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        // SEO varsayılanları (controller'lar sayfa bazında override eder)
        $seoDesc = 'Canlı açık artırma platformu — antika, koleksiyon ve özel parçalar için gerçek zamanlı müzayedeler.';
        $seoImg  = asset('assets/media/logos/logo-dark.svg');
        SEOMeta::setDescription($seoDesc);
        OpenGraph::setSiteName(config('app.name'))
            ->setType('website')
            ->setTitle(config('app.name'))
            ->setDescription($seoDesc);
        TwitterCard::setType('summary_large_image')
            ->setTitle(config('app.name'))
            ->setDescription($seoDesc)
            ->setImage($seoImg);

        Horizon::auth(function ($request) {
            return $request->user()?->hasRole('admin');
        });

        require_once app_path('helpers.php');
        Gate::policy(Auction::class, AuctionPolicy::class);
        Event::listen(Registered::class, SendEmailVerificationNotification::class);
    }
}
