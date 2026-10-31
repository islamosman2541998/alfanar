<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('cover')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('button_color', 20)->nullable();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('excerpt')->nullable();
            $table->json('description')->nullable();
            $table->json('cover')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('excerpt')->nullable();
            $table->json('description')->nullable();
            $table->json('location')->nullable();
            $table->string('year', 10)->nullable();
            $table->json('cover')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->json('cover')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('type', 10);
            $table->string('path');
            $table->string('thumb')->nullable();
            $table->string('poster')->nullable();
            $table->unsignedInteger('size')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('position')->nullable();
            $table->json('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('photo')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->json('label');
            $table->unsignedInteger('value')->default(0);
            $table->string('suffix', 10)->nullable();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->json('answer');
            $table->boolean('status')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->string('locale', 5)->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('location', 20)->index();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->json('title');
            $table->string('type', 20);
            $table->string('route')->nullable();
            $table->unsignedBigInteger('linkable_id')->nullable();
            $table->string('url')->nullable();
            $table->boolean('new_tab')->default(false);
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['menu_items', 'messages', 'faqs', 'features', 'stats', 'testimonials', 'media', 'pages', 'projects', 'services', 'sliders'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
