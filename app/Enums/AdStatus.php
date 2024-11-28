<?php
namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AdStatus: string implements HasLabel, HasColor, HasIcon
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in-progress';
    case COMPLETED = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In progress',
            self::COMPLETED => 'Completed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::COMPLETED => 'success',
            self::IN_PROGRESS => 'warning',
            self::PENDING => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::COMPLETED => 'heroicon-s-check-circle',
            self::IN_PROGRESS => 'heroicon-s-exclamation-circle',
            self::PENDING => 'heroicon-s-question-mark-circle',
        };
    }
}
