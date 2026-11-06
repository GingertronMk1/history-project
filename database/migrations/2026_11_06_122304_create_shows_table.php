<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Venue;
use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignIdFor(Year::class)->constrained();
            $table->foreignIdFor(Company::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Season::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Period::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Playwright::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Venue::class)->nullable()->constrained()->nullOnDelete();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->unsignedInteger('season_sort')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shows');
    }
};
