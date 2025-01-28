<?php

namespace Vocalio\LaravelCart;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-cart')
            ->hasConfigFile()
            ->hasMigration('create_cart_table');
    }

    public function boot(): void
    {
        $this->app->singleton(LaravelCart::class, function () {
            return new LaravelCart;
        });
    }
}
