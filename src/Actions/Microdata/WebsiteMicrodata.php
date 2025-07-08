<?php

namespace SmartCms\Kit\Actions\Microdata;

use Lorisleiva\Actions\Concerns\AsAction;

class WebsiteMicrodata
{
    use AsAction;

    public function handle(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => url('/'),
        ];
    }
}
