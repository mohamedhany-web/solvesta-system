<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'manager_id')) {
                $table->foreignId('manager_id')->nullable()->after('description')->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('departments', 'location')) {
                $table->string('location')->nullable()->after('manager_id');
            }
            if (!Schema::hasColumn('departments', 'phone')) {
                $table->string('phone', 20)->nullable()->after('location');
            }
            if (!Schema::hasColumn('departments', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('client_id')->constrained()->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['manager_id', 'location', 'phone', 'email']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
