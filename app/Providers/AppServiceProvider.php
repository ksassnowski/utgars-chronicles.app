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

namespace App\Providers;

use App\Export\CsvHistoryExporter;
use App\Export\HistoryExporter;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use YlsIdeas\FeatureFlags\Facades\Features;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(HistoryExporter::class, CsvHistoryExporter::class);

        Features::noBlade();
        Features::noScheduling();
        Features::noValidations();
        Features::noCommands();

        Collection::macro('transpose', function () {
            /** @phpstan-ignore-next-line */
            if ($this->isEmpty()) {
                return [];
            }

            /** @phpstan-ignore-next-line */
            if ($this->count() === 1) {
                /** @phpstan-ignore-next-line */
                return \array_map(static fn (string $item) => [$item], $this->first());
            }

            /** @phpstan-ignore-next-line */
            return \array_map(null, ...$this->all());
        });
    }
}
