<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('resume_files', function (Blueprint $table) {
            $table->id();
            $table->string('language', 2)->unique();
            $table->string('path');
            $table->string('original_name');
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('default_seo_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('resume_files');
        Schema::dropIfExists('contact_messages');
    }
};
