<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('training_participants')) {
            return;
        }

        if (Schema::hasColumn('training_participants', 'user_id')) {
            return;
        }

        Schema::table('training_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('training_id');
        });

        if (Schema::hasColumn('training_participants', 'employee_id')) {
            $participants = DB::table('training_participants')->select('id', 'employee_id')->get();
            foreach ($participants as $row) {
                $userId = Schema::hasTable('employees')
                    ? DB::table('employees')->where('id', $row->employee_id)->value('user_id')
                    : null;
                DB::table('training_participants')->where('id', $row->id)->update(['user_id' => $userId]);
            }

            DB::table('training_participants')->whereNull('user_id')->delete();

            Schema::table('training_participants', function (Blueprint $table) {
                $table->dropConstrainedForeignId('employee_id');
            });
        }

        Schema::table('training_participants', function (Blueprint $table) {
            if (Schema::hasColumn('training_participants', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('training_participants')) {
            return;
        }

        if (! Schema::hasColumn('training_participants', 'user_id')) {
            return;
        }

        Schema::table('training_participants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('training_participants', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->after('training_id')->constrained('employees')->cascadeOnDelete();
        });

        if (Schema::hasTable('employees')) {
            foreach (DB::table('training_participants')->select('id', 'user_id')->get() as $row) {
                $empId = DB::table('employees')->where('user_id', $row->user_id)->value('id');
                if ($empId) {
                    DB::table('training_participants')->where('id', $row->id)->update(['employee_id' => $empId]);
                }
            }
        }

        Schema::table('training_participants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
