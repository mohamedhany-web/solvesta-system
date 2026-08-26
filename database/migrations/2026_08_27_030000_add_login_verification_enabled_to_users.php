<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('login_verification_enabled')->default(false)->after('email_verified_at');
        });

        // موظفو المبيعات: بدون إجبار التحقق بالبريد عند الدخول
        DB::table('users')
            ->whereIn('id', function ($q) {
                $q->select('model_id')
                    ->from('model_has_roles')
                    ->where('model_type', 'App\\Models\\User')
                    ->whereIn('role_id', function ($r) {
                        $r->select('id')->from('roles')->where('name', 'sales_rep');
                    });
            })
            ->update(['login_verification_enabled' => false]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login_verification_enabled');
        });
    }
};
