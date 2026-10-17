<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('title')->nullable();
            $table->string('specialization')->nullable();
            $table->text('introduction')->nullable();
            $table->longText('background')->nullable();
            $table->longText('expertise')->nullable();
            $table->longText('interests')->nullable();
            $table->longText('approach')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url', 2048);
            $table->string('icon_key')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_visible')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_visible')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('icon_key')->nullable();
            $table->string('proficiency');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(false)->index();
            $table->timestamps();
            $table->index(['skill_category_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
        Schema::dropIfExists('skill_categories');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('profiles');
    }
};
