<?php

declare(strict_types=1);

namespace AvonezAuthClient\Console\Commands;

use Illuminate\Console\Command;

class AvonezAuthClientCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'avonez-auth-client:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package avonez-auth-client.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('AvonezAuthClient placeholder command executed.');

        return self::SUCCESS;
    }
}
