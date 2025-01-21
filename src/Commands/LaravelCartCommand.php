<?php

namespace Vocalio\LaravelCart\Commands;

use Illuminate\Console\Command;

class LaravelCartCommand extends Command
{
    public $signature = 'laravel-cart';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
