<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\YamlFrontMatter;

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
        $this->withProgressBar($files, function (string $file) use (&$keys) {
            try {
                $info = YamlFrontMatter::parse(Storage::disk('local')->get($file));
                foreach ($info->matter() as $key => $value) {
                    $keys[$key] ??= [];
                    $keys[$key][$value] = ($keys[$key][$value] ?? 0) + 1;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        });
        $this->newLine();
        $this->line(json_encode($keys, JSON_PRETTY_PRINT));
    }
}
