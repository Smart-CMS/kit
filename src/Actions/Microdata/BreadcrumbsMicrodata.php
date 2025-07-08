<?php

namespace SmartCms\Kit\Actions\Microdata;

use Lorisleiva\Actions\Concerns\AsAction;

class BreadcrumbsMicrodata
{
    use AsAction;

    public function handle(array $breadcrumbs = []): array
    {
        $microdata = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => hostname(),
                    'item' => host(),
                ],
            ],
        ];
        foreach ($breadcrumbs as $key => $breadcrumb) {
            $microdata['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $key + 1,
                'name' => $breadcrumb['name'] ?? hostname(),
                'item' => $breadcrumb['link'] ?? url('/'),
            ];
        }
        return $microdata;
    }
}
