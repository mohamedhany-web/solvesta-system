<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('trainings') || Schema::hasColumn('trainings', 'department_id')) {
            return;
        }

        Schema::table('trainings', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('instructor_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('trainings') || ! Schema::hasColumn('trainings', 'department_id')) {
            return;
        }

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
    }
};
