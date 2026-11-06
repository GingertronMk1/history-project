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
    public function handle(): int
    {
        $keys = [];
        $files = Storage::disk('local')->files('history-project/_shows', true);
        $this->withProgressBar($files, function (string $file) use (&$keys) {
            try {
                $info = YamlFrontMatter::parse(Storage::disk('local')->get($file));
                foreach ($info->matter() as $key => $value) {
                    if (! is_string($key) || ! (is_string($value) || is_numeric($value))) {
                        continue;
                    }
                    $keys[$key] ??= [];
                    $keys[$key][$value] = ($keys[$key][$value] ?? 0) + 1;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        });
        $this->newLine();
        ksort($keys);
        foreach ($keys as $key => $values) {
            $filteredVals = array_filter($values, fn ($v) => $v > 2);
            if (empty($filteredVals)) {
                continue;
            }
            $this->line($key);
            arsort($filteredVals);
            foreach ($filteredVals as $valueName => $valueCount) {
                $this->info(
                    sprintf(
                        '- %s appears %d times',
                        $valueName,
                        $valueCount
                    )
                );
            }
        }

        return self::SUCCESS;
    }
}
