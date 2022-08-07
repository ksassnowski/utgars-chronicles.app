<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Kai Sassnowski
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ksassnowski/utgars-chronicles.app
 */

namespace App\Providers;

use App\Export\CsvHistoryExporter;
use App\Export\HistoryExporter;
use App\MicroscopeEcho\Actions\AddEcho;
use App\MicroscopeEcho\Actions\AddIntervention;
use App\MicroscopeEcho\Actions\AddsEcho;
use App\MicroscopeEcho\Actions\AddsIntervention;
use App\MicroscopeEcho\Repository\DatabaseEchoGroupRepository;
use App\MicroscopeEcho\Repository\EchoGroupRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use YlsIdeas\FeatureFlags\Facades\Features;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            HistoryExporter::class,
            CsvHistoryExporter::class,
        );

        $this->app->bind(
            EchoGroupRepository::class,
            DatabaseEchoGroupRepository::class,
        );

        $this->app->bind(
            AddsIntervention::class,
            AddIntervention::class,
        );

        $this->app->bind(
            AddsEcho::class,
            AddEcho::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Features::noBlade();
        Features::noScheduling();
        Features::noValidations();
        Features::noCommands();

        Collection::macro('transpose', function () {
            if ($this->isEmpty()) {
                return [];
            }

            if ($this->count() === 1) {
                return \array_map(static fn (string $item) => [$item], $this->first());
            }

            /** @phpstan-ignore-next-line */
            return \array_map(null, ...$this->all());
        });
    }
}
