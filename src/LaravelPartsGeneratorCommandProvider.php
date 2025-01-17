<?php

namespace Sd883\LaravelPartsGenerator;

use Illuminate\Support\ServiceProvider;

class LaravelPartsGeneratorCommandProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\Commands\CreateMasterMaintenance::class,
        ]);
    }
}
