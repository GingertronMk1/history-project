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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\YamlFrontMatter\Document;
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
                $company = $this->getModelOrNull($data, 'company', Company::query());
                $season = $this->getModelOrNull($data, 'season', Season::query());
                $period = $this->getModelOrNull($data, 'period', Period::query());
                $playwright = $this->getModelOrNull($data, 'playwright', Playwright::query());
                $venue = $this->getModelOrNull($data, 'venue', Venue::query());
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

    /**
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * @param Builder<TModel> $query
     * @return TModel|null
     */
    private function getModelOrNull(Document $doc, string $matter, Builder $query, string $attr = 'name')
    {
        return $doc->matter($matter)
            ? $query->firstOrCreate([$attr => $doc->matter($matter)])
            : null;
    }
}
