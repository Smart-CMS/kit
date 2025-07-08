<?php

if (! function_exists('validateImage')) {
    function validateImage(mixed $image = null): string | array
    {
        $source = $image;
        if (is_array($image)) {
            $source = $image['source'] ?? null;
        }
        if (! $source) {
            if (is_string($image)) {
                return no_image()['source'];
            }

            return no_image();
        }
        if (! str_contains($source, 'storage')) {
            if (! str_starts_with($source, '/')) {
                $source = '/' . $source;
            }
            $source = asset('storage' . $source);
        }
        if (is_array($image)) {
            $image['source'] = $source;

            return $image;
        }

        return $source;
    }
}

if (! function_exists('no_image')) {
    function no_image(): array
    {
        return once(function () {
            $no_image = app('s')->get('no_image', []);
            if (! isset($no_image['source'])) {
                $no_image['source'] = '/no-image.webp';
            }
            $no_image['source'] = validateImage($no_image['source']);

            return $no_image;
        });
    }
}
if (! function_exists('logo')) {
    function logo(): array
    {
        $logo = app('s')->get('branding.logo', no_image());

        return validateImage($logo);
    }
}

if (! function_exists('company_name')) {
    function company_name(): string
    {
        return app('s')->get('company_name', config('app.name'));
    }
}
if (! function_exists('host')) {
    function host(): string
    {
        return url('/');
    }
}

if (! function_exists('hostname')) {
    function hostname(): string
    {
        return once(function () {
            return __('Hostname');
        });
    }
}
