<?php

declare(strict_types=1);

namespace App\Providers;

use App\Export\CsvHistoryExporter;
use App\Export\HistoryExporter;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

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

        Collection::macro('transpose', function () {
            if ($this->isEmpty()) {
                return [];
            }

            if ($this->count() === 1) {
                return \array_map(static fn (string $item) => [$item], $this->first());
            }

            return \array_map(null, ...$this->all());
        });
    }
}
