<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            if (! Schema::hasColumn('meetings', 'send_email_reminders')) {
                $table->boolean('send_email_reminders')->default(true)->after('department_id');
            }
            if (! Schema::hasColumn('meetings', 'reminder_1d_sent_at')) {
                $table->timestamp('reminder_1d_sent_at')->nullable()->after('send_email_reminders');
            }
            if (! Schema::hasColumn('meetings', 'reminder_1h_sent_at')) {
                $table->timestamp('reminder_1h_sent_at')->nullable()->after('reminder_1d_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $cols = [];
            foreach (['send_email_reminders', 'reminder_1d_sent_at', 'reminder_1h_sent_at'] as $col) {
                if (Schema::hasColumn('meetings', $col)) {
                    $cols[] = $col;
                }
            }
            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};
