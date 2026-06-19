<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('github_accounts')) {
            Schema::create('github_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('label')->nullable();
                $table->string('account_type', 20)->default('personal');
                $table->string('login', 100);
                $table->string('organization', 100)->nullable();
                $table->string('avatar_url')->nullable();
                $table->text('access_token');
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->foreignId('connected_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('connected_at')->nullable();
                $table->timestamps();

                $table->unique(['login', 'account_type', 'organization']);
            });
        }

        $this->migrateLegacyConnection();
    }

    public function down(): void
    {
        Schema::dropIfExists('github_accounts');
    }

    private function migrateLegacyConnection(): void
    {
        if (! Schema::hasTable('system_settings')) {
            return;
        }

        $tokenRow = DB::table('system_settings')->where('key', 'github_access_token')->first();
        if (! $tokenRow?->value) {
            return;
        }

        $get = fn (string $key) => DB::table('system_settings')->where('key', $key)->value('value');

        $accountType = $get('github_account_type') ?: ($get('github_organization') ? 'organization' : 'personal');
        $login = $get('github_connected_login') ?: 'legacy';
        $organization = $accountType === 'organization' ? $get('github_organization') : null;

        $exists = DB::table('github_accounts')
            ->where('login', $login)
            ->where('account_type', $accountType)
            ->where(function ($q) use ($organization) {
                if ($organization) {
                    $q->where('organization', $organization);
                } else {
                    $q->whereNull('organization');
                }
            })
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('github_accounts')->insert([
            'label' => 'الحساب الرئيسي',
            'account_type' => $accountType,
            'login' => $login,
            'organization' => $organization,
            'avatar_url' => $get('github_connected_avatar'),
            'access_token' => $tokenRow->value,
            'is_default' => true,
            'is_active' => true,
            'connected_at' => $get('github_connected_at') ?: now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
