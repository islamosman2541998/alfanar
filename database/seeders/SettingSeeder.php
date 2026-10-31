<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\MediaService;
use App\Support\Settings;
use Database\Seeders\Concerns\ImportsMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/** Default settings (only keys that do not exist yet are written). */
class SettingSeeder extends Seeder
{
    use ImportsMedia;

    /** Setting images taken from the real work photos (index in the media folder). */
    public const IMAGES = ['about_image' => 10, 'about_image_2' => 31, 'login_image' => 12, 'page_header_image' => 9];

    public function run(): void
    {
        $values = [
            // General
            'site_name' => ['ar' => 'الفنار للتشطيبات والديكور', 'en' => 'Alfanar Finishing & Decor'],
            'site_tagline' => ['ar' => 'تشطيبات وديكور', 'en' => 'Finishing & Decor'],
            'default_locale' => 'ar',

            // Appearance
            'primary_font' => 'Cairo',
            'color_primary' => '#e8a02a',
            'color_dark' => '#222326',
            'preloader' => 'home',
            'slider_delay' => '6',
            'cards_delay' => '4',
            'whatsapp_button' => '1',

            // Contact
            'phone' => '012 26603636',
            'phone_2' => '',
            'whatsapp' => '201226603636',
            'email' => '',
            'address' => ['ar' => 'القاهرة، مصر', 'en' => 'Cairo, Egypt'],
            'working_hours' => ['ar' => 'متاحون طوال الوقت', 'en' => 'Always open'],
            'map_embed' => 'https://maps.google.com/maps?q=Cairo%2C%20Egypt&z=11&output=embed',

            // Social
            'facebook' => 'https://www.facebook.com/profile.php?id=61571166542381',

            // SEO
            'seo_title' => ['ar' => 'الفنار للتشطيبات والديكور | تشطيب شقق وفلل في القاهرة', 'en' => 'Alfanar Finishing & Decor | Apartment & Villa Finishing in Cairo'],
            'seo_description' => [
                'ar' => 'الفنار للتشطيبات والديكور: تشطيب شقق وفلل بالكامل، تصميم داخلي، أسقف جبس بورد، كهرباء وسباكة، أرضيات ورخام، مطابخ ودريسنج في القاهرة.',
                'en' => 'Alfanar Finishing & Decor: full apartment and villa finishing, interior design, gypsum ceilings, electrical and plumbing, flooring, kitchens and dressing rooms in Cairo.',
            ],
            'seo_keywords' => [
                'ar' => 'تشطيب شقق, تشطيبات, ديكور, تصميم داخلي, جبس بورد, تشطيب فلل, القاهرة',
                'en' => 'apartment finishing, finishing company, interior design, decor, gypsum board, villa finishing, Cairo',
            ],
            'seo_indexing' => '1',
            'schema_type' => 'HomeAndConstructionBusiness',
            'schema_price_range' => '$$',
            'seo_home_title' => ['ar' => '', 'en' => ''],
            'seo_services_title' => ['ar' => 'خدمات التشطيب والديكور', 'en' => 'Finishing & Decor Services'],
            'seo_services_description' => ['ar' => 'تعرف على خدمات الفنار: تشطيب كامل، تصميم داخلي، جبس بورد، كهرباء، سباكة، أرضيات، مطابخ ودريسنج.', 'en' => 'Explore Alfanar services: full finishing, interior design, gypsum board, electrical, plumbing, flooring, kitchens and dressing rooms.'],
            'seo_projects_title' => ['ar' => 'أعمالنا في التشطيب والديكور', 'en' => 'Our Finishing & Decor Projects'],
            'seo_projects_description' => ['ar' => 'صور وفيديوهات حقيقية من مشاريع الفنار للتشطيبات والديكور.', 'en' => 'Real photos and videos from Alfanar finishing and decor projects.'],
            'seo_about_title' => ['ar' => 'من نحن', 'en' => 'About Us'],
            'seo_contact_title' => ['ar' => 'تواصل معنا واطلب معاينة', 'en' => 'Contact Us & Request a Visit'],
            'seo_contact_description' => ['ar' => 'تواصل مع الفنار للتشطيبات والديكور عبر الهاتف أو واتساب أو فورم التواصل واطلب معاينة لوحدتك.', 'en' => 'Contact Alfanar Finishing & Decor by phone, WhatsApp or the contact form and request a site visit.'],
            'seo_faq_title' => ['ar' => 'الأسئلة الشائعة عن التشطيب', 'en' => 'Finishing FAQ'],

            // Login page
            'login_title' => ['ar' => 'لوحة تحكم الفنار', 'en' => 'Alfanar dashboard'],
            'login_subtitle' => ['ar' => 'أدر محتوى الموقع والخدمات والأعمال والرسائل من مكان واحد.', 'en' => 'Manage website content, services, projects and messages in one place.'],

            // Home sections
            'home_about' => '1',
            'home_services' => '1',
            'home_projects' => '1',
            'home_stats' => '1',
            'home_testimonials' => '1',
            'home_contact' => '1',

            // Footer
            'footer_about' => [
                'ar' => 'مش بنشطّب مكان، بنحوّل المساحة لبيت يشبهك وتحب تعيش كل تفاصيله.',
                'en' => 'We don’t just finish a space, we turn it into a home that feels like you, with every detail worth living.',
            ],
            'copyright' => ['ar' => '© :year الفنار للتشطيبات والديكور. جميع الحقوق محفوظة.', 'en' => '© :year Alfanar Finishing & Decor. All rights reserved.'],
        ];

        $values += $this->settingImages();

        $existing = Setting::pluck('key')->all();
        Settings::set(array_diff_key($values, array_flip($existing)));
    }

    /** Logo, favicon and section images from the media folder. */
    protected function settingImages(): array
    {
        if (! ($path = $this->mediaPath()) || Setting::where('key', 'logo')->exists()) {
            return [];
        }

        $media = app(MediaService::class);
        $images = [];

        if (is_file($logo = $path.'/logo.jpg')) {
            Storage::disk('public')->put($images['logo'] = 'settings/logo.jpg', file_get_contents($logo));

            $source = imagecreatefromjpeg($logo);
            $icon = imagecreatetruecolor(192, 192);
            imagecopyresampled($icon, $source, 0, 0, 0, 0, 192, 192, imagesx($source), imagesy($source));
            ob_start();
            imagepng($icon);
            Storage::disk('public')->put($images['favicon'] = 'settings/favicon.png', ob_get_clean());
        }

        foreach (static::IMAGES as $key => $index) {
            if ($stored = $this->image($index, 'settings')) {
                $images[$key] = $stored['path'];
                $media->delete(['path' => $stored['thumb']]);
            }
        }

        return $images;
    }
}
