<?php

namespace App\Support;

/**
 * Describes every dashboard setting: tabs => fields.
 *
 * Field types: text, textarea, code, url, email, number, color, select, toggle, image
 * `tr` => true stores the value per language (ar / en).
 */
class SettingsSchema
{
    public const SEO_PAGES = ['home', 'services', 'projects', 'about', 'contact', 'faq'];

    public static function tabs(): array
    {
        return [
            'general' => ['icon' => 'bi-sliders', 'label' => 'General', 'fields' => [
                ['key' => 'site_name', 'type' => 'text', 'tr' => true, 'label' => 'Site name'],
                ['key' => 'site_tagline', 'type' => 'text', 'tr' => true, 'label' => 'Tagline'],
                ['key' => 'logo', 'type' => 'image', 'label' => 'Logo', 'hint' => 'PNG or SVG with transparent background is recommended'],
                ['key' => 'favicon', 'type' => 'image', 'label' => 'Favicon', 'hint' => 'Square PNG, 512 x 512'],
                ['key' => 'default_locale', 'type' => 'select', 'label' => 'Default site language', 'options' => ['ar' => 'العربية', 'en' => 'English']],
            ]],
            'appearance' => ['icon' => 'bi-palette', 'label' => 'Appearance', 'fields' => [
                ['key' => 'primary_font', 'type' => 'select', 'label' => 'Primary font', 'options' => ['Cairo' => 'Cairo', 'Tajawal' => 'Tajawal'], 'hint' => 'Used for body text. The other font is used for headings.'],
                ['key' => 'color_primary', 'type' => 'color', 'label' => 'Brand color'],
                ['key' => 'color_dark', 'type' => 'color', 'label' => 'Dark color'],
                ['key' => 'preloader', 'type' => 'select', 'label' => 'Loading screen', 'options' => ['home' => 'Home page only', 'all' => 'All pages', 'off' => 'Disabled']],
                ['key' => 'slider_delay', 'type' => 'number', 'label' => 'Hero slider autoplay (seconds)'],
                ['key' => 'cards_delay', 'type' => 'number', 'label' => 'Cards sliders autoplay (seconds)'],
                ['key' => 'page_header_image', 'type' => 'image', 'label' => 'Inner pages header image'],
                ['key' => 'whatsapp_button', 'type' => 'toggle', 'label' => 'Floating WhatsApp button'],
            ]],
            'contact' => ['icon' => 'bi-telephone', 'label' => 'Contact info', 'fields' => [
                ['key' => 'phone', 'type' => 'text', 'label' => 'Phone'],
                ['key' => 'phone_2', 'type' => 'text', 'label' => 'Second phone'],
                ['key' => 'whatsapp', 'type' => 'text', 'label' => 'WhatsApp number', 'hint' => 'International format without + e.g. 201226603636'],
                ['key' => 'email', 'type' => 'email', 'label' => 'Email'],
                ['key' => 'address', 'type' => 'text', 'tr' => true, 'label' => 'Address'],
                ['key' => 'working_hours', 'type' => 'text', 'tr' => true, 'label' => 'Working hours'],
                ['key' => 'map_embed', 'type' => 'url', 'label' => 'Google Maps embed link', 'hint' => 'Google Maps > Share > Embed a map > copy the src link only'],
                ['key' => 'notify_email', 'type' => 'email', 'label' => 'Send new messages to email', 'hint' => 'Leave empty to disable (requires mail settings in .env)'],
            ]],
            'social' => ['icon' => 'bi-share', 'label' => 'Social media', 'fields' => [
                ['key' => 'facebook', 'type' => 'url', 'label' => 'Facebook'],
                ['key' => 'instagram', 'type' => 'url', 'label' => 'Instagram'],
                ['key' => 'tiktok', 'type' => 'url', 'label' => 'TikTok'],
                ['key' => 'youtube', 'type' => 'url', 'label' => 'YouTube'],
                ['key' => 'x', 'type' => 'url', 'label' => 'X (Twitter)'],
                ['key' => 'linkedin', 'type' => 'url', 'label' => 'LinkedIn'],
            ]],
            'seo' => ['icon' => 'bi-search', 'label' => 'SEO', 'fields' => array_merge([
                ['key' => 'seo_title', 'type' => 'text', 'tr' => true, 'label' => 'Default title', 'hint' => 'Shown in Google results and browser tab (50-60 characters)', 'serp' => true],
                ['key' => 'seo_description', 'type' => 'textarea', 'tr' => true, 'label' => 'Default description', 'hint' => 'Shown under the title in Google results (120-160 characters)', 'serp' => true],
                ['key' => 'seo_keywords', 'type' => 'text', 'tr' => true, 'label' => 'Keywords', 'hint' => 'Comma separated'],
                ['key' => 'og_image', 'type' => 'image', 'label' => 'Share image (Open Graph)', 'hint' => 'Shown when the link is shared on WhatsApp / Facebook. Leave empty to use the logo'],
                ['key' => 'seo_indexing', 'type' => 'toggle', 'label' => 'Allow search engines to index the site'],
                ['key' => 'google_verification', 'type' => 'text', 'label' => 'Google Search Console verification code'],
                ['key' => 'bing_verification', 'type' => 'text', 'label' => 'Bing verification code'],
                ['key' => 'schema_type', 'type' => 'select', 'label' => 'Business type (structured data)', 'options' => ['HomeAndConstructionBusiness' => 'Home & construction business', 'GeneralContractor' => 'General contractor', 'LocalBusiness' => 'Local business']],
                ['key' => 'schema_price_range', 'type' => 'text', 'label' => 'Price range (structured data)', 'hint' => 'e.g. $$'],
            ], static::seoPageFields())],
            'pixels' => ['icon' => 'bi-bar-chart', 'label' => 'Pixels & tracking', 'fields' => [
                ['key' => 'facebook_pixel', 'type' => 'text', 'label' => 'Facebook (Meta) Pixel ID'],
                ['key' => 'ga4_id', 'type' => 'text', 'label' => 'Google Analytics 4 ID', 'hint' => 'G-XXXXXXXXXX'],
                ['key' => 'gtm_id', 'type' => 'text', 'label' => 'Google Tag Manager ID', 'hint' => 'GTM-XXXXXXX'],
                ['key' => 'tiktok_pixel', 'type' => 'text', 'label' => 'TikTok Pixel ID'],
                ['key' => 'snapchat_pixel', 'type' => 'text', 'label' => 'Snapchat Pixel ID'],
                ['key' => 'head_code', 'type' => 'code', 'label' => 'Custom code before </head>'],
                ['key' => 'body_code', 'type' => 'code', 'label' => 'Custom code before </body>'],
            ]],
            'login' => ['icon' => 'bi-door-open', 'label' => 'Login page', 'fields' => [
                ['key' => 'login_image', 'type' => 'image', 'label' => 'Side image'],
                ['key' => 'login_title', 'type' => 'text', 'tr' => true, 'label' => 'Title'],
                ['key' => 'login_subtitle', 'type' => 'textarea', 'tr' => true, 'label' => 'Subtitle'],
            ]],
            'home' => ['icon' => 'bi-house', 'label' => 'Home page', 'fields' => [
                ['key' => 'home_about', 'type' => 'toggle', 'label' => 'Show about section'],
                ['key' => 'about_image', 'type' => 'image', 'label' => 'About section image'],
                ['key' => 'about_image_2', 'type' => 'image', 'label' => 'About section second image'],
                ['key' => 'home_services', 'type' => 'toggle', 'label' => 'Show services section'],
                ['key' => 'home_projects', 'type' => 'toggle', 'label' => 'Show projects section'],
                ['key' => 'home_stats', 'type' => 'toggle', 'label' => 'Show statistics section'],
                ['key' => 'home_testimonials', 'type' => 'toggle', 'label' => 'Show testimonials section'],
                ['key' => 'home_contact', 'type' => 'toggle', 'label' => 'Show contact section'],
            ]],
            'footer' => ['icon' => 'bi-layout-text-window-reverse', 'label' => 'Footer', 'fields' => [
                ['key' => 'footer_about', 'type' => 'textarea', 'tr' => true, 'label' => 'About text'],
                ['key' => 'copyright', 'type' => 'text', 'tr' => true, 'label' => 'Copyright text', 'hint' => ':year is replaced with the current year'],
            ]],
        ];
    }

    /** Per-page title & description shown in Google results. */
    protected static function seoPageFields(): array
    {
        return collect(static::SEO_PAGES)->flatMap(fn ($page) => [
            ['key' => "seo_{$page}_title", 'type' => 'text', 'tr' => true, 'label' => 'Title', 'page' => $page, 'serp' => true],
            ['key' => "seo_{$page}_description", 'type' => 'textarea', 'tr' => true, 'label' => 'Description', 'page' => $page, 'serp' => true],
        ])->all();
    }

    public static function fields(?string $tab = null): array
    {
        $tabs = static::tabs();

        return $tab ? ($tabs[$tab]['fields'] ?? []) : collect($tabs)->pluck('fields')->flatten(1)->all();
    }
}
