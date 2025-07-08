<?php

namespace SmartCms\Kit\Http\Handlers;

use Lorisleiva\Actions\Concerns\AsAction;

class RobotsHandler
{
    use AsAction;

    public function handle()
    {
        $robots = "User-agent: *\nDisallow: /";
        if (app('s')->get('indexation', false)) {
            $robots = "User-agent: *\nDisallow: /admin\nDisallow: /cart\nDisallow: /checkout\nDisallow: /search\nDisallow: /register\nDisallow: /reset-password\nDisallow: /*page*\nSitemap: " . route('sitemap') . "\nHost: " . request()->getHost();
        }

        return response($robots)->header('Content-Type', 'text/plain');
    }
}
