<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('stage')->default('applied')->after('status');
            $table->unsignedTinyInteger('ats_score')->nullable()->after('stage');
            $table->string('ats_grade')->nullable()->after('ats_score');
            $table->json('parsed_cv')->nullable()->after('ats_grade');
            $table->json('extracted_skills')->nullable()->after('parsed_cv');
            $table->unsignedSmallInteger('years_experience')->nullable()->after('extracted_skills');
            $table->string('education_level')->nullable()->after('years_experience');
            $table->longText('cv_text')->nullable()->after('education_level');
            $table->string('parse_status')->default('pending')->after('cv_text');
            $table->text('parse_error')->nullable()->after('parse_status');
            $table->timestamp('parsed_at')->nullable()->after('parse_error');
            $table->timestamp('evaluated_at')->nullable()->after('parsed_at');

            $table->index(['job_posting_id', 'stage']);
            $table->index(['job_posting_id', 'ats_score']);
            $table->index('parse_status');
        });

        Schema::create('job_ats_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->string('code', 60);
            $table->string('label');
            $table->text('description')->nullable();
            $table->enum('type', ['auto', 'manual'])->default('auto');
            $table->unsignedTinyInteger('weight')->default(10);
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['job_posting_id', 'code']);
        });

        Schema::create('application_ats_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->cascadeOnDelete();
            $table->foreignId('job_ats_criterion_id')->constrained('job_ats_criteria')->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedTinyInteger('max_score')->default(100);
            $table->text('notes')->nullable();
            $table->json('evidence')->nullable();
            $table->enum('source', ['system', 'reviewer'])->default('system');
            $table->foreignId('scored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['job_application_id', 'job_ats_criterion_id'], 'app_criterion_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_ats_scores');
        Schema::dropIfExists('job_ats_criteria');

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropIndex(['job_posting_id', 'stage']);
            $table->dropIndex(['job_posting_id', 'ats_score']);
            $table->dropIndex(['parse_status']);
            $table->dropColumn([
                'stage',
                'ats_score',
                'ats_grade',
                'parsed_cv',
                'extracted_skills',
                'years_experience',
                'education_level',
                'cv_text',
                'parse_status',
                'parse_error',
                'parsed_at',
                'evaluated_at',
            ]);
        });
    }
};
