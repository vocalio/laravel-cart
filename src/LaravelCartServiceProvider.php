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
            ->hasTranslations()
            ->hasMigration('create_cart_table');
    }

    public function boot(): void
    {
        parent::boot();

        $this->app->singleton(LaravelCart::class, function () {
            return new LaravelCart;
        });
    }
}
