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

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SignUpsChart extends LineChartWidget
{
    protected static ?string $heading = 'New users this month';

    /**
     * @return array{datasets: array<int, mixed>, labels: array<int, string>}
     */
    protected function getData(): array
    {
        $rangeStart = now()->startOfMonth();
        $rangeEnd = now()->endOfMonth();
        $dates = CarbonPeriod::create($rangeStart, $rangeEnd)->toArray();

        /** @var Collection<int, \stdClass> $userData */
        $userData = DB::table('users')
            ->selectRaw("DATE_FORMAT(created_at, '%y-%m-%d') as date, COUNT(*) as cnt")
            ->whereBetween('created_at', [
                $rangeStart,
                $rangeEnd,
            ])
            ->groupBy('date')
            ->orderByDesc('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Sign ups',
                    'data' => collect($dates)
                        ->reduce(static function (array $data, CarbonInterface $date) use ($userData) {
                            $data[] = $userData
                                ->firstWhere('date', $date->format('y-m-d'))
                                ?->cnt ?: 0;

                            return $data;
                        }, []),
                ],
            ],
            'labels' => collect($dates)
                ->map(static fn (CarbonInterface $date) => $date->format('y-m-d'))
                ->all(),
        ];
    }
}
