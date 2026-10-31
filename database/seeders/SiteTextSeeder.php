<?php

namespace Database\Seeders;

use App\Models\SiteText;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Adds missing website texts without overwriting edited ones. */
class SiteTextSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/site_texts.php' as $key => [$ar, $en]) {
            SiteText::firstOrCreate(['key' => $key], [
                'group' => Str::before($key, '.'),
                'value' => ['ar' => $ar, 'en' => $en],
            ]);
        }
    }
}
