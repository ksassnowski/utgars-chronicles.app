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
            if ($this->count() <= 1) {
                $this->push([]);
            }

            return array_map(null, ...$this->all());
        });
    }
}
