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
        $crew = [];
        $files = Storage::disk('local')->files('history-project/_shows', true);
        $this->withProgressBar($files, function (string $file) use (&$keys, &$crew) {
            try {
                $info = YamlFrontMatter::parse(Storage::disk('local')->get($file));
                foreach ($info->matter() as $key => $value) {
                    if (!is_string($key) || !(is_string($value) || is_numeric($value))) {
                        continue;
                    }
                    $keys[$key] ??= [];
                    $keys[$key][(string)$value] = ($keys[$key][(string)$value] ?? 0) + 1;
                }
                foreach ($info->matter('crew', []) as ['role' => $crewRole]) {
                    $crew[$crewRole] = ($crew[$crewRole] ?? 0) + 1;
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        });

        $this->newLine();

        arsort($crew);

        $similars = [];

        foreach ($crew as $role => $value) {
            if ($value < 5) {
                $mostLikely = array_reduce(
                    array_keys(array_filter($crew, fn ($v) => $v > 5)),
                    function ($carry, $item) use ($role) {
                        if ($role === $item) {
                            return $carry;
                        }
                        if (similar_text($item, $role) > similar_text($carry, $role)) {
                            return $item;
                        } else {
                            return $carry;
                        }
                    },
                    '*****'
                );
                $perc = 0;
                similar_text($role, $mostLikely, $perc);
                $similars[] = ['role' => $role, 'word' => $mostLikely, 'percent' => $perc];
            }
        }

        uasort($similars, function ($a, $b) {
            return $b['percent'] <=> $a['percent'];
        });

        $this->output->table(['role', 'word', 'percent'], $similars);

        $this->info('storing key data');
        Storage::disk('local')->put('data.json', json_encode(['keys' => $keys, 'crew' => $crew], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        $this->newLine();
        ksort($keys);
        foreach ($keys as $key => $values) {
            $filteredVals = array_filter($values, fn($v) => $v > 2);
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
