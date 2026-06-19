<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        DB::table('tasks')->where('status', 'completed')->update(['status' => 'done']);
        DB::table('tasks')->where('status', 'review')->update(['status' => 'code_review']);
        DB::table('tasks')->where('status', 'pending')->update(['status' => 'backlog']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status', 32)->default('backlog')->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        DB::table('tasks')->where('status', 'done')->update(['status' => 'completed']);
        DB::table('tasks')->where('status', 'code_review')->update(['status' => 'review']);
        DB::table('tasks')->where('status', 'backlog')->update(['status' => 'todo']);
        DB::table('tasks')->where('status', 'qa_testing')->update(['status' => 'review']);
        DB::table('tasks')->where('status', 'client_review')->update(['status' => 'review']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['todo', 'in_progress', 'review', 'completed', 'cancelled'])->default('todo')->change();
        });
    }
};
