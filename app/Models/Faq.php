<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasPublishing, HasTranslations;

    protected $fillable = ['question', 'answer', 'status', 'sort_order'];

    protected array $translatable = ['question', 'answer'];

    protected $casts = ['status' => 'boolean'];
}
