<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasCover, HasPublishing, HasTranslations;

    protected $fillable = ['title', 'description', 'cover', 'button_text', 'button_link', 'button_color', 'status', 'sort_order'];

    protected array $translatable = ['title', 'description', 'button_text'];

    protected $casts = ['status' => 'boolean'];
}
