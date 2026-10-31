<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Stat;
use App\Models\Testimonial;
use Database\Seeders\Concerns\ImportsMedia;
use Illuminate\Database\Seeder;

/**
 * Initial content written from the company page and posts,
 * with the real work photos and videos from the media folder.
 */
class ContentSeeder extends Seeder
{
    use ImportsMedia;

    public function run(): void
    {
        if (Service::exists()) {
            $this->command?->warn('Content already exists, skipped.');

            return;
        }

        if (! $this->mediaPath()) {
            $this->command?->warn('Media folder not found (SEED_MEDIA_PATH), content is created without photos.');
        }

        $services = $this->services();
        $this->projects($services);
        $this->sliders();
        $this->sections();
        $this->pages();
    }

    protected function services(): array
    {
        $rows = [
            'finishing' => [
                'slug' => 'full-finishing',
                'title' => ['تشطيب الشقق والفلل بالكامل', 'Full apartment & villa finishing'],
                'excerpt' => ['شطّب وحدتك بالكامل واستلمها على المفتاح، من التأسيس حتى آخر لمسة.', 'Complete turnkey finishing, from groundwork to the final touch.'],
                'description' => [
                    "نستلم الوحدة على الطوب الأحمر ونسلمها جاهزة للسكن. فريق واحد يتابع كل المراحل: المباني والمحارة، تأسيس الكهرباء والسباكة، الأرضيات، الأسقف والإضاءة، الدهانات، وحتى تركيب المطابخ والدريسنج.\n\nبنبدأ بمعاينة وتصور كامل للمساحة، وبنحدد معاك الخامات والميزانية والجدول الزمني قبل أي خطوة، علشان تتابع شغلك وأنت مطمّن.",
                    "We take the unit from bare brick and hand it over ready to live in. One team handles every stage: masonry and plastering, electrical and plumbing groundwork, flooring, ceilings and lighting, paint, and the installation of kitchens and dressing rooms.\n\nWe start with a site visit and a full concept, and agree on materials, budget and timeline before any work begins, so you can follow your project with peace of mind.",
                ],
                'video' => '4.00.58', 'cover' => 9, 'gallery' => [21, 75, 52, 105, 144, 115],
            ],
            'design' => [
                'slug' => 'interior-design-decor',
                'title' => ['التصميم الداخلي والديكور', 'Interior design & decor'],
                'excerpt' => ['تصميمات مودرن وكلاسيك مدروسة تجمع بين الذوق والراحة والعملية.', 'Modern and classic interiors that balance taste, comfort and function.'],
                'description' => [
                    "التصميم وحده مش كفاية، القيمة الحقيقية في تناسق التفاصيل وجودة الخامات ودقة التنفيذ. بنشتغل معاك على تصور كامل للمساحة: توزيع الأثاث، الألوان، الإضاءة، وتجاليد الحوائط.\n\nسواء كان ذوقك مودرن هادي أو كلاسيك فخم، بنحوّل الفكرة لتصميم واضح وبعدها لتنفيذ مطابق على أرض الواقع.",
                    "Design alone is not enough; the real value lies in harmonious details, quality materials and precise execution. We work with you on a complete concept for the space: furniture layout, colours, lighting and wall finishes.\n\nWhether your taste is calm modern or rich classic, we turn the idea into a clear design and then into a faithful execution on site.",
                ],
                'video' => '4.00.41', 'cover' => 7, 'gallery' => [4, 12, 10, 0, 1, 31],
            ],
            'gypsum' => [
                'slug' => 'gypsum-ceilings-lighting',
                'title' => ['أسقف الجبس بورد والإضاءة', 'Gypsum ceilings & lighting'],
                'excerpt' => ['أسقف معلقة وإضاءة مخفية وبروفايل لينير بتصميمات عصرية.', 'Suspended ceilings, hidden lighting and linear profiles.'],
                'description' => [
                    "الأسقف والإضاءة هي اللي بتدي المكان روحه. بننفذ أسقف الجبس بورد بكل أنواعها، مع الإضاءة المخفية، والسبوت لايت، وبروفايلات الإضاءة الخطية، ومعالجة فواصل الأسقف بدقة.\n\nبنهتم بالتشطيب النهائي للسقف ومستوى الاستقامة علشان النتيجة تبان نظيفة تحت أي إضاءة.",
                    "Ceilings and lighting give a space its character. We build all types of gypsum board ceilings with hidden lighting, spotlights and linear light profiles, with precise treatment of every joint.\n\nWe care about the final finish and flatness of each ceiling so the result looks clean under any light.",
                ],
                'video' => '4.01.03', 'cover' => 67, 'gallery' => [73, 89, 94, 103, 157, 79, 23],
            ],
            'kitchens' => [
                'slug' => 'kitchens-dressing-rooms',
                'title' => ['المطابخ والدريسنج روم', 'Kitchens & dressing rooms'],
                'excerpt' => ['مطابخ ودواليب ودريسنج بتصميم وتفصيل حسب المساحة.', 'Custom kitchens, wardrobes and dressing rooms made to fit your space.'],
                'description' => [
                    "بنصمم وننفذ المطابخ والدريسنج روم والدواليب على مقاس المساحة بالظبط، بخامات وألوان متناسقة مع باقي التصميم، مع إضاءة داخلية وإكسسوارات عملية.\n\nكل قطعة بتتفصل علشان تستغل كل سنتيمتر وتسهّل استخدامك اليومي.",
                    "We design and build kitchens, dressing rooms and wardrobes to the exact size of your space, with materials and colours that match the rest of the design, plus interior lighting and practical accessories.\n\nEvery piece is made to use every centimetre and make daily use easier.",
                ],
                'video' => '3.57.16', 'cover' => 19, 'gallery' => [13, 11, 17, 18, 20, 21],
            ],
            'flooring' => [
                'slug' => 'flooring-marble-porcelain',
                'title' => ['الأرضيات والرخام والبورسلين', 'Flooring, marble & porcelain'],
                'excerpt' => ['تركيب بورسلين ورخام وباركيه بمستوى واستقامة دقيقة.', 'Porcelain, marble and parquet installed with precise levels.'],
                'description' => [
                    "بنركب البورسلين والرخام بالأحجام الكبيرة باستخدام موازين التسوية لضمان سطح مستوي وفواصل منتظمة، بالإضافة لتجاليد الحوائط بالبورسلين وأرضيات الباركيه.\n\nبنختار معاك نوع الخامة واتجاه التركيب اللي يناسب المساحة والإضاءة.",
                    "We install large format porcelain and marble using levelling systems for a perfectly flat surface and even joints, along with porcelain wall cladding and parquet floors.\n\nWe help you choose the material and the laying direction that suit the space and its light.",
                ],
                'video' => '4.00.51', 'cover' => 63, 'gallery' => [30, 57, 65, 83, 90, 91],
            ],
            'electrical' => [
                'slug' => 'electrical-works',
                'title' => ['أعمال الكهرباء', 'Electrical works'],
                'excerpt' => ['تأسيس كهرباء منظم بخامات معتمدة ولوحات توزيع آمنة.', 'Organised wiring with certified materials and safe distribution boards.'],
                'description' => [
                    "التأسيس الصح هو أساس أمان البيت. بننفذ تأسيس الكهرباء بالكامل: توزيع المواسير والعلب بالليزر، الأسلاك بالألوان والمقاطع الصحيحة، ولوحات التوزيع بتقسيم واضح لكل دائرة.\n\nكل خط بيتعمل له اختبار قبل التقفيل، علشان تستلم شبكة آمنة ومنظمة وسهلة الصيانة.",
                    "Proper groundwork is the base of a safe home. We carry out complete electrical installations: conduit and box layout with laser levels, colour coded wiring with correct cross sections, and distribution boards with clearly separated circuits.\n\nEvery line is tested before closing, so you receive a safe, organised and easy to maintain network.",
                ],
                'cover' => 43, 'gallery' => [35, 42, 48, 86, 109, 170],
            ],
            'plumbing' => [
                'slug' => 'plumbing-works',
                'title' => ['أعمال السباكة', 'Plumbing works'],
                'excerpt' => ['تأسيس مياه وصرف بمواسير عالية الجودة واختبار ضغط قبل التشطيب.', 'Water and drainage networks with quality pipes and pressure testing.'],
                'description' => [
                    "بننفذ تأسيس السباكة للحمامات والمطابخ: مواسير التغذية الساخن والبارد، شبكات الصرف بالميول الصحيحة، وتجهيز أماكن الأجهزة الصحية بدقة.\n\nبنعمل اختبار ضغط للشبكة قبل أي تشطيب، علشان نضمن شبكة سليمة من غير تسريب.",
                    "We install plumbing for bathrooms and kitchens: hot and cold supply lines, drainage networks with correct slopes, and precise preparation for every sanitary fixture.\n\nThe network is pressure tested before any finishing to ensure a sound, leak free system.",
                ],
                'cover' => 145, 'gallery' => [137, 108, 156, 158, 162, 175],
            ],
            'walls' => [
                'slug' => 'paints-wall-finishes',
                'title' => ['الدهانات وتجاليد الحوائط', 'Paints & wall finishes'],
                'excerpt' => ['دهانات حديثة وبانوهات وتجاليد تضيف عمقاً للمساحة.', 'Modern paints, panels and wall cladding that add depth to a room.'],
                'description' => [
                    "الدهانات والتجاليد هي اللمسة اللي بتكمل التصميم. بننفذ تأسيس الحوائط والمعجون والدهانات الحديثة، والبانوهات الكلاسيك، وتجاليد الخشب والرخام.\n\nبنهتم بتجهيز السطح قبل الدهان لأنه سر النتيجة الناعمة اللي بتعيش سنين.",
                    "Paint and wall finishes are the touch that completes a design. We handle wall preparation, putty and modern paints, classic wall panels, and wood or marble cladding.\n\nWe pay close attention to surface preparation, the secret behind a smooth finish that lasts for years.",
                ],
                'cover' => 64, 'gallery' => [85, 34, 31, 96, 97],
            ],
            'aluminum' => [
                'slug' => 'aluminum-windows',
                'title' => ['الألوميتال والشبابيك', 'Aluminium windows'],
                'excerpt' => ['شبابيك وأبواب ألوميتال بقطاعات قوية وعزل جيد.', 'Aluminium windows and doors with strong profiles and good insulation.'],
                'description' => [
                    "بنوفر ونركب شبابيك وأبواب الألوميتال بقطاعات وألوان مختلفة، مع زجاج سنجل أو دبل وتفاصيل زخرفية حسب طلبك.\n\nالتركيب بيتم بعد ظبط الفتحات بدقة لضمان إحكام الغلق والعزل.",
                    "We supply and install aluminium windows and doors in various profiles and colours, with single or double glazing and decorative details on request.\n\nInstallation follows precise preparation of openings for a tight seal and good insulation.",
                ],
                'cover' => 155, 'gallery' => [114, 130, 133, 168, 171, 177],
            ],
            'masonry' => [
                'slug' => 'masonry-plastering',
                'title' => ['أعمال المباني والمحارة', 'Masonry & plastering'],
                'excerpt' => ['مباني وتقسيمات ومحارة بمستوى وزوايا مضبوطة.', 'Walls, partitions and plastering with accurate levels and angles.'],
                'description' => [
                    "بنبدأ من الأساس: بناء الحوائط والتقسيمات الداخلية وتعديل الفتحات، ثم أعمال المحارة بالقوامات والزوايا المضبوطة علشان كل اللي بعدها يتركب صح.\n\nالمراحل الأولى هي اللي بتحدد جودة التشطيب النهائي، علشان كده بنديها نفس الاهتمام.",
                    "We start from the base: building walls and internal partitions and adjusting openings, followed by plastering with accurate guides and corners so everything that follows fits right.\n\nThe early stages define the quality of the final finish, so they get the same attention.",
                ],
                'cover' => 45, 'gallery' => [29, 38, 44, 54, 62, 88],
            ],
        ];

        $services = [];
        $order = 0;

        foreach ($rows as $key => $row) {
            $service = Service::create([
                'slug' => $row['slug'],
                'title' => ['ar' => $row['title'][0], 'en' => $row['title'][1]],
                'excerpt' => ['ar' => $row['excerpt'][0], 'en' => $row['excerpt'][1]],
                'description' => ['ar' => $row['description'][0], 'en' => $row['description'][1]],
                'cover' => $this->cover($row['video'] ?? null, $row['cover'], 'services'),
                'is_featured' => $order < 6,
                'status' => true,
                'sort_order' => $order++,
            ]);
            $this->gallery($service, 'services/gallery', $row['gallery']);
            $services[$key] = $service;
        }

        return $services;
    }

    protected function projects(array $services): void
    {
        // [service, slug, title, excerpt, cover video (or null), cover photo, photos, gallery videos]
        $rows = [
            ['kitchens', 'modern-kitchen-island', ['مطبخ مودرن بجزيرة وإضاءة مخفية', 'Modern kitchen with island and hidden lighting'],
                ['مطبخ مفتوح بجزيرة خشبية ووحدات عالية بإضاءة داخلية، مع دمج الأجهزة داخل الدواليب.', 'An open kitchen with a timber island and tall units with interior lighting and integrated appliances.'],
                '3.57.20', 21, [13, 11, 17, 22, 9, 2], ['3.57.29', '3.57.36', '3.57.42']],
            ['finishing', 'modern-apartment-full-finishing', ['تشطيب شقة مودرن بالكامل', 'Modern apartment full finishing'],
                ['تشطيب كامل لشقة سكنية: أرضيات رخام، أسقف جبس بإضاءة خطية، ونجف وتجاليد حوائط.', 'Complete finishing for a residential apartment: marble floors, gypsum ceilings with linear lighting, chandeliers and wall finishes.'],
                '4.00.55', 144, [75, 52, 115, 103, 50], ['4.00.58', '4.00.51', '4.00.12']],
            ['kitchens', 'walk-in-dressing-room', ['دريسنج روم بواجهات زجاج وإضاءة', 'Walk-in dressing room with glass fronts'],
                ['دريسنج متكامل بواجهات زجاج وأرفف مضاءة ووحدات أدراج في المنتصف.', 'A complete dressing room with glass fronts, lit shelves and a central drawer unit.'],
                '3.58.21', 20, [18, 14, 16, 15, 6], ['3.58.26']],
            ['kitchens', 'modern-kitchens', ['مطابخ مودرن بخامات لامعة', 'Modern high gloss kitchens'],
                ['مطابخ بوحدات لامعة وإضاءة مدمجة وأجهزة بيلت إن، بتفصيل على مقاس كل مساحة.', 'High gloss kitchens with integrated lighting and built-in appliances, made to fit each space.'],
                '3.58.34', 19, [], ['4.00.21', '3.57.16', '3.57.12']],
            ['design', 'modern-bedrooms', ['غرف نوم مودرن بإضاءة مخفية', 'Modern bedrooms with hidden lighting'],
                ['غرف نوم بسراير بإضاءة سفلية، وتجاليد حوائط، ودواليب ووحدات تسريحة مدمجة.', 'Bedrooms with under-lit beds, wall cladding, and built-in wardrobes and dressing tables.'],
                null, 7, [4, 5, 8, 10, 12], ['4.00.41']],
            ['finishing', 'modern-bathrooms', ['حمامات بتشطيب مودرن', 'Modern bathrooms'],
                ['حمامات برخام وبورسلين وإضاءة مخفية خلف المرايا، ووحدات معلقة وفواصل زجاج.', 'Bathrooms with marble and porcelain, hidden lighting behind mirrors, wall hung units and glass partitions.'],
                null, 74, [76, 46, 56, 25, 58], []],
            ['gypsum', 'gypsum-ceilings-residence', ['أسقف جبس بورد وإضاءة لسكن خاص', 'Gypsum ceilings & lighting for a residence'],
                ['أسقف معلقة بمستويات مختلفة مع بروفايلات إضاءة خطية وإضاءة مخفية ونجف.', 'Multi level suspended ceilings with linear light profiles, hidden lighting and chandeliers.'],
                null, 67, [73, 89, 94, 157, 105, 120, 123, 79, 23, 24, 28, 51], ['4.01.03']],
            ['design', 'tv-units-shelving', ['وحدات تلفزيون ومكتبات بإضاءة', 'TV units and lit shelving'],
                ['وحدات تلفزيون معلقة ومكتبات خشب بإضاءة مدمجة، بتصميم وتفصيل على مقاس الحائط.', 'Wall hung TV units and timber shelving with integrated lighting, designed and made to fit the wall.'],
                null, 1, [0, 3, 97], []],
            ['walls', 'classic-wall-panels', ['بانوهات حوائط كلاسيك', 'Classic wall panels'],
                ['بانوهات كلاسيك بلون أخضر هادئ مع إضاءة جدارية ونجفة كريستال.', 'Classic wall panels in a calm green tone with wall lights and a crystal chandelier.'],
                null, 64, [85, 34, 31, 96], []],
            ['flooring', 'porcelain-marble-flooring', ['أرضيات بورسلين ورخام', 'Porcelain & marble flooring'],
                ['تركيب بورسلين بالأحجام الكبيرة بموازين التسوية وتجاليد حوائط بنفس الخامة.', 'Large format porcelain laid with levelling systems and matching wall cladding.'],
                null, 63, [30, 57, 65, 71, 83, 90, 91, 95, 69], []],
            ['electrical', 'electrical-installation', ['تأسيس كهرباء شقة بالكامل', 'Complete apartment electrical installation'],
                ['تأسيس كهرباء منظم بالألوان مع لوحات توزيع مقسمة وعلب مضبوطة بالليزر.', 'Colour coded electrical installation with organised distribution boards and laser aligned boxes.'],
                null, 43, [35, 37, 42, 48, 55, 86, 109, 117, 122, 163, 166, 167, 170, 195], []],
            ['plumbing', 'plumbing-networks', ['تأسيس شبكات سباكة', 'Plumbing networks installation'],
                ['تأسيس مياه وصرف للحمامات والمطابخ مع شبكة صرف خارجية واختبار ضغط.', 'Water and drainage for bathrooms and kitchens, with an external drainage line and pressure testing.'],
                null, 145, [137, 108, 156, 158, 161, 162, 175, 186], []],
            ['aluminum', 'aluminum-windows-project', ['شبابيك ألوميتال', 'Aluminium windows'],
                ['توريد وتركيب شبابيك ألوميتال بقطاعات مختلفة وزجاج بتفاصيل زخرفية.', 'Supply and installation of aluminium windows in different profiles, including decorative glass.'],
                null, 155, [114, 130, 133, 168, 171, 177], []],
            ['masonry', 'masonry-plastering-works', ['أعمال مباني ومحارة', 'Masonry & plastering works'],
                ['بناء حوائط وتقسيمات داخلية ومحارة بقوامات مضبوطة كبداية لمشروع تشطيب كامل.', 'Building walls, internal partitions and accurate plastering as the start of a full finishing project.'],
                null, 45, [29, 38, 44, 47, 54, 62, 68, 84, 88, 92, 78], []],
        ];

        foreach ($rows as $order => [$service, $slug, $title, $excerpt, $video, $cover, $images, $videos]) {
            $project = Project::create([
                'service_id' => $services[$service]->id,
                'slug' => $slug,
                'title' => ['ar' => $title[0], 'en' => $title[1]],
                'excerpt' => ['ar' => $excerpt[0], 'en' => $excerpt[1]],
                'description' => ['ar' => $excerpt[0], 'en' => $excerpt[1]],
                'cover' => $this->cover($video, $cover, 'projects'),
                'is_featured' => $order < 8,
                'status' => true,
                'sort_order' => $order,
            ]);
            // When the cover is a video, the cover photo opens the gallery
            $this->gallery($project, 'projects/gallery', $video ? array_merge([$cover], $images) : $images, $videos);
        }
    }

    protected function sliders(): void
    {
        $slides = [
            [
                'cover' => $this->image(21, 'sliders'),
                'title' => ['تشطيبات وديكور بتفاصيل محسوبة', 'Finishing and decor with every detail considered'],
                'description' => ['في فرق بين مكان متشطب ومكان كل تفصيلة فيه محسوبة.', 'There is a difference between a finished space and one where every detail is considered.'],
                'button' => ['اطلب معاينة', 'Request a visit'], 'link' => '/contact',
            ],
            [
                'cover' => $this->image(64, 'sliders'),
                'title' => ['تصميم كلاسيك يليق بذوقك', 'Classic interiors that match your taste'],
                'description' => ['من أول تصور للتصميم لحد آخر لمسة في التنفيذ.', 'From the first design concept to the final touch on site.'],
                'button' => ['شاهد أعمالنا', 'View our work'], 'link' => '/projects',
            ],
            [
                'cover' => $this->image(7, 'sliders'),
                'title' => ['مساحات مودرن مريحة وعملية', 'Modern spaces, comfortable and practical'],
                'description' => ['نجمع بين الذوق والراحة والعملية في كل مساحة.', 'Taste, comfort and practicality in every space.'],
                'button' => ['خدماتنا', 'Our services'], 'link' => '/services',
            ],
            [
                'cover' => $this->image(19, 'sliders'),
                'title' => ['مطابخ ودريسنج على مقاس المساحة', 'Kitchens and dressing rooms made to fit'],
                'description' => ['تفصيل كامل بخامات مختارة وإضاءة مدمجة.', 'Fully custom pieces with selected materials and integrated lighting.'],
                'button' => ['تواصل معنا', 'Contact us'], 'link' => '/contact',
            ],
        ];

        foreach ($slides as $order => $slide) {
            Slider::create([
                'title' => ['ar' => $slide['title'][0], 'en' => $slide['title'][1]],
                'description' => ['ar' => $slide['description'][0], 'en' => $slide['description'][1]],
                'cover' => $slide['cover'],
                'button_text' => ['ar' => $slide['button'][0], 'en' => $slide['button'][1]],
                'button_link' => $slide['link'],
                'button_color' => '#e8a02a',
                'status' => true,
                'sort_order' => $order,
            ]);
        }
    }

    protected function sections(): void
    {
        $stats = [
            [Project::count(), '+', 'مشروع منفذ في المعرض', 'Projects in our portfolio'],
            [Service::count(), '', 'خدمات تشطيب وديكور', 'Finishing & decor services'],
            [13000, '+', 'متابع على فيسبوك', 'Facebook followers'],
            [24, '/7', 'متاحون للتواصل', 'Available to talk'],
        ];

        foreach ($stats as $order => [$value, $suffix, $ar, $en]) {
            Stat::create(['value' => $value, 'suffix' => $suffix, 'label' => ['ar' => $ar, 'en' => $en], 'status' => true, 'sort_order' => $order]);
        }

        $features = [
            ['جودة الخامات', 'Quality materials', 'بنختار معاك خامات معروفة ومناسبة لاستخدامك وميزانيتك.', 'We help you choose trusted materials that suit your use and budget.'],
            ['دقة في التنفيذ', 'Precise execution', 'تفاصيل التنفيذ هي اللي بتثبت جودة التصميم، وعلشان كده بنتابع كل مرحلة.', 'Execution proves the design, so every stage is closely supervised.'],
            ['سرعة في التسليم', 'Fast delivery', 'جدول زمني واضح من البداية والتزام بمواعيد كل مرحلة.', 'A clear timeline from day one and commitment to every milestone.'],
            ['أفضل جودة بأفضل سعر', 'Best quality, fair price', 'عروض أسعار واضحة ومفصلة بدون مفاجآت أثناء التنفيذ.', 'Clear, detailed quotes with no surprises during the work.'],
            ['من التصميم للتسليم', 'Design to handover', 'فريق واحد مسؤول عن التصميم والتأسيس والتشطيب والديكور.', 'One team responsible for design, groundwork, finishing and decor.'],
            ['متابعة مستمرة', 'Continuous follow up', 'بنشاركك صور وفيديوهات من الموقع علشان تتابع شغلك أول بأول.', 'We share photos and videos from site so you can follow progress.'],
        ];

        foreach ($features as $order => [$ar, $en, $dAr, $dEn]) {
            Feature::create(['title' => ['ar' => $ar, 'en' => $en], 'description' => ['ar' => $dAr, 'en' => $dEn], 'status' => true, 'sort_order' => $order]);
        }

        $faqs = [
            ['إزاي أبدأ مع الفنار؟', 'How do I start with Alfanar?',
                'ابعتلنا نوع الوحدة ومساحتها وموقعها من خلال فورم التواصل أو واتساب، وهنتواصل معاك لتحديد معاد معاينة ونتكلم عن رؤيتك.',
                'Send us the unit type, area and location through the contact form or WhatsApp, and we will contact you to schedule a site visit and discuss your vision.'],
            ['هل بتنفذوا تشطيب كامل ولا مراحل محددة؟', 'Do you handle full finishing or specific stages?',
                'الاتنين. ممكن نستلم الوحدة ونسلمها كاملة، أو ننفذ مرحلة معينة زي الكهرباء أو السباكة أو الأسقف أو الأرضيات.',
                'Both. We can take the unit and deliver it complete, or carry out a specific stage such as electrical, plumbing, ceilings or flooring.'],
            ['هل بتقدموا تصميم قبل التنفيذ؟', 'Do you provide a design before execution?',
                'أيوه، بنشتغل على تصور للتصميم والخامات قبل التنفيذ علشان تكون شايف النتيجة النهائية وتعتمدها.',
                'Yes, we prepare a design concept and material selection before execution so you can see and approve the final result.'],
            ['بتشتغلوا في أنهي مناطق؟', 'Which areas do you work in?',
                'مقرنا في القاهرة، وبنشتغل في القاهرة الكبرى والمناطق القريبة. تواصل معانا وقولنا موقع وحدتك.',
                'We are based in Cairo and work across Greater Cairo and nearby areas. Contact us with your unit location.'],
            ['إزاي بيتحدد سعر التشطيب؟', 'How is the finishing price determined?',
                'السعر بيعتمد على المساحة والحالة الحالية للوحدة والخامات المختارة ومستوى التشطيب. بعد المعاينة بنقدملك عرض سعر مفصل.',
                'The price depends on the area, the current condition of the unit, the chosen materials and the finishing level. After the visit we provide a detailed quote.'],
            ['هل أقدر أتابع الشغل أثناء التنفيذ؟', 'Can I follow the work during execution?',
                'طبعاً، بنشاركك صور وفيديوهات من الموقع بشكل مستمر، وتقدر تزور الموقع في أي وقت بالتنسيق معانا.',
                'Of course. We regularly share photos and videos from site, and you can visit at any time by arrangement.'],
        ];

        foreach ($faqs as $order => [$qAr, $qEn, $aAr, $aEn]) {
            Faq::create(['question' => ['ar' => $qAr, 'en' => $qEn], 'answer' => ['ar' => $aAr, 'en' => $aEn], 'status' => true, 'sort_order' => $order]);
        }

        // Sample reviews: hidden until replaced with real client reviews from the dashboard.
        $reviews = [
            ['اسم العميل', 'Client name', 'شقة سكنية', 'Apartment', 'اكتب هنا رأي العميل الحقيقي ثم فعّل الظهور من لوحة التحكم.', 'Write the real client review here, then activate it from the dashboard.'],
            ['اسم العميل', 'Client name', 'فيلا', 'Villa', 'اكتب هنا رأي العميل الحقيقي ثم فعّل الظهور من لوحة التحكم.', 'Write the real client review here, then activate it from the dashboard.'],
            ['اسم العميل', 'Client name', 'مطبخ ودريسنج', 'Kitchen & dressing', 'اكتب هنا رأي العميل الحقيقي ثم فعّل الظهور من لوحة التحكم.', 'Write the real client review here, then activate it from the dashboard.'],
        ];

        foreach ($reviews as $order => [$nAr, $nEn, $pAr, $pEn, $cAr, $cEn]) {
            Testimonial::create([
                'name' => ['ar' => $nAr, 'en' => $nEn],
                'position' => ['ar' => $pAr, 'en' => $pEn],
                'content' => ['ar' => $cAr, 'en' => $cEn],
                'rating' => 5,
                'status' => false,
                'sort_order' => $order,
            ]);
        }
    }

    protected function pages(): void
    {
        $page = Page::create([
            'slug' => 'how-we-work',
            'title' => ['ar' => 'طريقة شغلنا', 'en' => 'How we work'],
            'description' => [
                'ar' => "١. المعاينة: بنزور الوحدة ونسمع منك احتياجاتك وذوقك والميزانية المتاحة.\n\n٢. التصميم والخامات: بنجهز تصور للمساحة ونختار معاك الخامات والألوان.\n\n٣. عرض السعر والجدول الزمني: عرض مفصل بكل بند ومدة تنفيذ واضحة.\n\n٤. التنفيذ: فريق واحد بيتابع كل المراحل من التأسيس حتى التشطيب النهائي، مع مشاركتك بالصور والفيديو أول بأول.\n\n٥. التسليم: بنراجع كل التفاصيل معاك قبل ما نسلمك الوحدة جاهزة.",
                'en' => "1. Site visit: we visit the unit and listen to your needs, taste and budget.\n\n2. Design and materials: we prepare a concept for the space and choose materials and colours with you.\n\n3. Quote and timeline: a detailed quote for every item and a clear schedule.\n\n4. Execution: one team follows every stage from groundwork to final finishing, sharing photos and videos as work progresses.\n\n5. Handover: we review every detail with you before handing over the finished unit.",
            ],
            'cover' => $this->image(13, 'pages'),
            'is_featured' => true,
            'status' => true,
            'sort_order' => 0,
        ]);

        $this->gallery($page, 'pages/gallery', [27, 69, 93, 33, 41, 39]);
    }
}
