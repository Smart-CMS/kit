<?php

namespace SmartCms\Kit\VariableTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use SmartCms\TemplateBuilder\Support\VariableTypeInterface;

class EmailType implements VariableTypeInterface
{
    public static function make(): self
    {
        return new self;
    }

    public static function getName(): string
    {
        return 'email';
    }

    public function getDefaultValue(): mixed
    {
        return 'example@example.com';
    }

    public function getSchema(string $name): Field | Component
    {
        return Select::make($name)->options(collect(app('s')->get('company_info.emails', []))->pluck('value'));
    }

    public function getValue(mixed $value): mixed
    {
        foreach (app('s')->get('company_info.emails', []) as $key => $item) {
            if ($key == $value) {
                return $item['value'] ?? $this->getDefaultValue();
            }
        }

        return $this->getDefaultValue();
    }
}
