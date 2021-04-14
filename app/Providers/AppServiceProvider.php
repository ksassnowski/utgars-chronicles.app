<?php declare(strict_types=1);

namespace App\Providers;

use App\Export\HistoryExporter;
use App\Export\CsvHistoryExporter;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(HistoryExporter::class, CsvHistoryExporter::class);

        Collection::macro('transpose', function () {
            if ($this->isEmpty()) {
                return [];
            }

            if ($this->count() === 1) {
                return array_map(fn (string $item) => [$item], $this->first());
            }

            return array_map(null, ...$this->all());
        });
    }
}
