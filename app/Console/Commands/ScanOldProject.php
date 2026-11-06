<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Yaml\Yaml;

#[Signature('app:scan-old-project')]
#[Description('Command description')]
class ScanOldProject extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = [];
        $files = Storage::disk('local')->files('history-project/_shows', true);
        $this->withProgressBar($files, function ($file) use (&$keys) {
            try {
                $info = YamlFrontMatter::parse(Storage::disk('local')->get($file));
                $keys = \array_unique(array_merge($keys, array_keys($info->matter())));
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        });
        $this->newLine();
        sort($keys);
        $this->output->listing($keys);
    }
}
