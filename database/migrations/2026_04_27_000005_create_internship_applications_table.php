<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('internship_applications')) {
            return;
        }

        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();

            $table->string('full_name');
            $table->string('email')->index();
            $table->string('phone', 60)->nullable();

            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->string('level')->nullable(); // e.g. student / fresh grad

            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();

            $table->string('cv_path')->nullable();
            $table->text('message')->nullable();

            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->unique(['training_id', 'email'], 'internship_applications_training_email_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};

