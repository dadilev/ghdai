<?php
namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AdTemplateStatus: string implements HasLabel, HasColor, HasIcon
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Active',
            self::ARCHIVED => 'Archived',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::ACTIVE => 'success',
            self::ARCHIVED => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-s-check-circle',
            self::ARCHIVED => 'heroicon-s-exclamation-circle',
            self::DRAFT => 'heroicon-s-question-mark-circle',
        };
    }
}
