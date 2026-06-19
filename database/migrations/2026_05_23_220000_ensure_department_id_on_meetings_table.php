<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('meetings') || Schema::hasColumn('meetings', 'department_id')) {
            return;
        }

        Schema::table('meetings', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('organizer_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('meetings') || ! Schema::hasColumn('meetings', 'department_id')) {
            return;
        }

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
    }
};
