<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_system_feature_updates')) {
            return;
        }

        if (! $this->hasIndex('client_system_feature_updates', 'csfu_feat_vis_idx')) {
            Schema::table('client_system_feature_updates', function (Blueprint $table) {
                $table->index(['client_system_feature_id', 'visibility'], 'csfu_feat_vis_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('client_system_feature_updates') && $this->hasIndex('client_system_feature_updates', 'csfu_feat_vis_idx')) {
            Schema::table('client_system_feature_updates', function (Blueprint $table) {
                $table->dropIndex('csfu_feat_vis_idx');
            });
        }
    }

    private function hasIndex(string $table, string $name): bool
    {
        foreach (Schema::getIndexes($table) as $index) {
            if (($index['name'] ?? '') === $name) {
                return true;
            }
        }

        return false;
    }
};
