<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $after = Schema::hasColumn('employees', 'national_id') ? 'national_id' : 'phone';

            if (! Schema::hasColumn('employees', 'full_name_ar')) {
                $table->string('full_name_ar')->nullable()->after($after);
            }
            if (! Schema::hasColumn('employees', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (! Schema::hasColumn('employees', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
            if (! Schema::hasColumn('employees', 'religion')) {
                $table->string('religion', 30)->nullable();
            }
            if (! Schema::hasColumn('employees', 'marital_status')) {
                $table->string('marital_status', 30)->nullable();
            }
            if (! Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality', 80)->nullable()->default('مصري');
            }
            if (! Schema::hasColumn('employees', 'birth_place')) {
                $table->string('birth_place')->nullable();
            }
            if (! Schema::hasColumn('employees', 'national_id_address')) {
                $table->text('national_id_address')->nullable();
            }
            if (! Schema::hasColumn('employees', 'id_issue_date')) {
                $table->date('id_issue_date')->nullable();
            }
            if (! Schema::hasColumn('employees', 'id_expiry_date')) {
                $table->date('id_expiry_date')->nullable();
            }
            if (! Schema::hasColumn('employees', 'military_status')) {
                $table->string('military_status', 30)->nullable();
            }
            if (! Schema::hasColumn('employees', 'phone_secondary')) {
                $table->string('phone_secondary', 30)->nullable();
            }
            if (! Schema::hasColumn('employees', 'whatsapp_phone')) {
                $table->string('whatsapp_phone', 30)->nullable();
            }
            if (! Schema::hasColumn('employees', 'education_level')) {
                $table->string('education_level', 40)->nullable();
            }
            if (! Schema::hasColumn('employees', 'university')) {
                $table->string('university')->nullable();
            }
            if (! Schema::hasColumn('employees', 'faculty')) {
                $table->string('faculty')->nullable();
            }
            if (! Schema::hasColumn('employees', 'major')) {
                $table->string('major')->nullable();
            }
            if (! Schema::hasColumn('employees', 'graduation_year')) {
                $table->unsignedSmallInteger('graduation_year')->nullable();
            }
            if (! Schema::hasColumn('employees', 'education_notes')) {
                $table->text('education_notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $cols = [
                'full_name_ar', 'date_of_birth', 'gender', 'religion', 'marital_status',
                'nationality', 'birth_place', 'national_id_address', 'id_issue_date',
                'id_expiry_date', 'military_status', 'phone_secondary', 'whatsapp_phone',
                'education_level', 'university', 'faculty', 'major', 'graduation_year',
                'education_notes',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('employees', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
