<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;
use App\Models\Year;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\YamlFrontMatter;

#[Signature('app:seed-from-old-project')]
#[Description('Command description')]
class SeedFromOldProject extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $errors = [];
        $files = Storage::disk('local')->files('history-project/_shows', true);
        $this->withProgressBar($files, function (string $file) use (&$errors) {
            try {
                $data = YamlFrontMatter::parse(Storage::disk('local')->get($file));
                $yearTitle = preg_replace('/.*\/(\d\d_\d\d)\/.*/', '$1', $file);
                $year = Year::query()->firstOrCreate(['name' => $yearTitle]);
                $company = $data->matter('company') ? Company::query()->firstOrCreate(['name' => $data->matter('company')]) : null;
                $season = $data->matter('season') ? Season::query()->firstOrCreate(['name' => $data->matter('season')]) : null;
                $period = $data->matter('period') ? Period::query()->firstOrCreate(['name' => $data->matter('period')]) : null;
                $playwright = $data->matter('playwright') ? Playwright::query()->firstOrCreate(['name' => $data->matter('playwright')]) : null;
                $venue = $data->matter('venue') ? Venue::query()->firstOrCreate(['name' => $data->matter('venue')]) : null;
                Show::query()->create([
                    'title' => $data->matter('title'),
                    'description' => $data->body(),
                    'year_id' => $year->id,
                    'company_id' => $company?->id,
                    'season_id' => $season?->id,
                    'period_id' => $period?->id,
                    'playwright_id' => $playwright?->id,
                    'venue_id' => $venue?->id,
                    'date_start' => $data->matter('date_start'),
                    'date_end' => $data->matter('date_end'),
                    'season_sort' => $data->matter('season_sort') ?? 0,
                ]);
            } catch (\Exception $e) {
                $errors[$file] = $e->getMessage();
            }
        });

        $this->info('Completed with '.count($errors).' errors.');
        foreach ($errors as $file => $error) {
            $this->error($file.': '.$error);
        }

        return self::SUCCESS;
    }
}
