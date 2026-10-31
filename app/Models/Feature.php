<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasPublishing, HasTranslations;

    protected $fillable = ['title', 'description', 'status', 'sort_order'];

    protected array $translatable = ['title', 'description'];

    protected $casts = ['status' => 'boolean'];
}
