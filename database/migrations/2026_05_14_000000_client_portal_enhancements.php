<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('type', 64);
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('action_url', 2000)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'read_at']);
            $table->index(['client_id', 'created_at']);
        });

        Schema::create('client_shared_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('document_type', 64)->default('general');
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('mime', 128)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['client_id', 'created_at']);
        });

        Schema::create('client_portal_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->morphs('feedbackable');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['feedbackable_type', 'feedbackable_id', 'client_id'], 'client_portal_feedback_unique');
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'payment_link')) {
                $table->string('payment_link', 2000)->nullable()->after('notes');
            }
        });

        Schema::table('financial_invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('financial_invoices', 'payment_link')) {
                $table->string('payment_link', 2000)->nullable()->after('notes');
            }
        });

        Schema::table('client_accounts', function (Blueprint $table) {
            try {
                $table->dropUnique(['client_id']);
            } catch (\Throwable) {
                // Index may not exist on older installs
            }
            if (! Schema::hasColumn('client_accounts', 'portal_role')) {
                $table->string('portal_role', 32)->default('owner')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portal_feedbacks');
        Schema::dropIfExists('client_shared_documents');
        Schema::dropIfExists('client_notifications');

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'payment_link')) {
                $table->dropColumn('payment_link');
            }
        });

        Schema::table('financial_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('financial_invoices', 'payment_link')) {
                $table->dropColumn('payment_link');
            }
        });

        Schema::table('client_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('client_accounts', 'portal_role')) {
                $table->dropColumn('portal_role');
            }
            $table->unique('client_id');
        });
    }
};
