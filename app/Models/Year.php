<?php

namespace App\Models;

use Database\Factories\YearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
#[Fillable(['name', 'description'])]
class Year extends Model
{
    /** @use HasFactory<YearFactory> */
    use HasFactory;
}
