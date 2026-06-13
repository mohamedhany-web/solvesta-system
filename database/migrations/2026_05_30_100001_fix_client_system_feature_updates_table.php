<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_system_feature_updates')) {
            Schema::create('client_system_feature_updates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_system_feature_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('body');
                $table->string('visibility', 16)->default('client');
                $table->unsignedBigInteger('created_by_user_id')->nullable();
                $table->unsignedBigInteger('created_by_client_account_id')->nullable();
                $table->timestamps();

                $table->foreign('created_by_user_id', 'csfu_user_fk')->references('id')->on('users')->nullOnDelete();
                $table->foreign('created_by_client_account_id', 'csfu_client_acct_fk')->references('id')->on('client_accounts')->nullOnDelete();
                $table->index(['client_system_feature_id', 'visibility']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_system_feature_updates');
    }
};
