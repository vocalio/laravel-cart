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
            ->hasMigration('create_laravel_cart_table');
    }
}
