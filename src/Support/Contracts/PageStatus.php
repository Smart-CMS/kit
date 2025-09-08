<?php

namespace SmartCms\Kit\Support\Contracts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PageStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('kit::admin.draft'),
            self::Scheduled => __('kit::admin.scheduled'),
            default => __('kit::admin.published')
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Scheduled => 'info',
            default => 'success'
        };
    }
}
