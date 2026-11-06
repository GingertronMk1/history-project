<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:scan-old-project')]
#[Description('Command description')]
class ScanOldProject extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        \storage_path('history-project/_shows');
    }
}
