<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Filament\Widgets;

use App\History;
use App\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

final class StatsOverview extends BaseWidget
{
    /**
     * @return array<int, Card>
     */
    protected function getCards(): array
    {
        return [
            Card::make('Registered Users', User::query()->count()),
            Card::make('Histories created', History::query()->count()),
        ];
    }
}
