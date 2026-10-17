<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'profiles' => ['full_name_fa', 'title_fa', 'specialization_fa', 'introduction_fa', 'background_fa', 'expertise_fa', 'interests_fa', 'approach_fa'],
            'site_settings' => ['site_name_fa', 'default_seo_description_fa'],
            'social_links' => ['label_fa'],
            'skill_categories' => ['name_fa'],
            'skills' => ['name_fa'],
            'work_experiences' => ['company_fa', 'job_title_fa', 'employment_type_fa', 'location_fa', 'description_fa', 'responsibilities_fa', 'achievements_fa'],
            'educations' => ['institution_fa', 'degree_fa', 'field_of_study_fa', 'description_fa'],
            'project_categories' => ['name_fa'],
            'technologies' => ['name_fa'],
            'projects' => ['title_fa', 'summary_fa', 'body_fa', 'role_fa', 'challenges_fa', 'solutions_fa', 'seo_title_fa', 'seo_description_fa'],
            'project_images' => ['alt_text_fa'],
            'blog_categories' => ['name_fa'],
            'blog_posts' => ['title_fa', 'excerpt_fa', 'content_fa', 'featured_image_alt_fa', 'seo_title_fa', 'seo_description_fa'],
        ];

        foreach ($columns as $tableName => $fields) {
            Schema::table($tableName, function (Blueprint $table) use ($fields): void {
                foreach ($fields as $field) {
                    if (in_array($field, ['background_fa', 'expertise_fa', 'interests_fa', 'approach_fa', 'body_fa', 'content_fa'], true)) {
                        $table->longText($field)->nullable();
                    } elseif (in_array($field, ['introduction_fa', 'description_fa', 'responsibilities_fa', 'achievements_fa', 'summary_fa', 'role_fa', 'challenges_fa', 'solutions_fa', 'excerpt_fa'], true)) {
                        $table->text($field)->nullable();
                    } else {
                        $table->string($field)->nullable();
                    }
                }
            });
        }
    }

    public function down(): void
    {
        $columns = [
            'profiles' => ['full_name_fa', 'title_fa', 'specialization_fa', 'introduction_fa', 'background_fa', 'expertise_fa', 'interests_fa', 'approach_fa'],
            'site_settings' => ['site_name_fa', 'default_seo_description_fa'],
            'social_links' => ['label_fa'],
            'skill_categories' => ['name_fa'],
            'skills' => ['name_fa'],
            'work_experiences' => ['company_fa', 'job_title_fa', 'employment_type_fa', 'location_fa', 'description_fa', 'responsibilities_fa', 'achievements_fa'],
            'educations' => ['institution_fa', 'degree_fa', 'field_of_study_fa', 'description_fa'],
            'project_categories' => ['name_fa'],
            'technologies' => ['name_fa'],
            'projects' => ['title_fa', 'summary_fa', 'body_fa', 'role_fa', 'challenges_fa', 'solutions_fa', 'seo_title_fa', 'seo_description_fa'],
            'project_images' => ['alt_text_fa'],
            'blog_categories' => ['name_fa'],
            'blog_posts' => ['title_fa', 'excerpt_fa', 'content_fa', 'featured_image_alt_fa', 'seo_title_fa', 'seo_description_fa'],
        ];

        foreach ($columns as $tableName => $fields) {
            Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn($fields));
        }
    }
};
