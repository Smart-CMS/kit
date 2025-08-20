<?php

namespace SmartCms\Kit\VariableTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Fluent;
use SmartCms\TemplateBuilder\Support\VariableTypeInterface;

class SocialsType implements VariableTypeInterface
{
    public static function make(): self
    {
        return new self;
    }

    public static function getName(): string
    {
        return 'socials';
    }

    public function getDefaultValue(): mixed
    {
        return collect([[
            'name' => 'Facebook',
            'link' => 'https://www.facebook.com',
            'image' => 'https://www.facebook.com/favicon.ico',
        ]])->map(function ($item) {
            return new Fluent([
                'name' => $item['name'],
                'link' => $item['link'],
                'image' => $item['image'],
            ]);
        });
    }

    public function getSchema(string $name): Field | Component
    {
        return Select::make($name)->options(collect(app('s')->get('branding.socials', []))->pluck('name'))->multiple();
    }

    public function getValue(mixed $value): mixed
    {
        return collect(app('s')->get('branding.socials', []))->only($value)->map(function ($item) {
            return new Fluent([
                'name' => $item['name'],
                'link' => $item['link'],
                'image' => $item['image'],
            ]);
        });
    }
}
