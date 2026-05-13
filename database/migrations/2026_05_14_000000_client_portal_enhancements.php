<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_notifications')) {
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
        }

        if (! Schema::hasTable('client_shared_documents')) {
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
        }

        if (! Schema::hasTable('client_portal_feedbacks')) {
            Schema::create('client_portal_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->cascadeOnDelete();
                $table->morphs('feedbackable');
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(['feedbackable_type', 'feedbackable_id', 'client_id'], 'client_portal_feedback_unique');
            });
        }

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

        // MySQL: لا يمكن حذف UNIQUE على client_id طالما المفتاح الأجنبي يعتمد عليه.
        if (Schema::hasTable('client_accounts') && ! Schema::hasColumn('client_accounts', 'portal_role')) {
            try {
                Schema::table('client_accounts', function (Blueprint $table) {
                    $table->dropForeign(['client_id']);
                });
            } catch (\Throwable) {
                // قد يكون المفتاح محذوفاً من محاولة سابقة
            }

            Schema::table('client_accounts', function (Blueprint $table) {
                $indexes = Schema::getConnection()->getSchemaBuilder()->getIndexes('client_accounts');
                foreach ($indexes as $index) {
                    $cols = $index['columns'] ?? [];
                    if ($cols === ['client_id'] && ($index['unique'] ?? false) && ! ($index['primary'] ?? false)) {
                        $table->dropIndex($index['name']);
                        break;
                    }
                }
            });

            Schema::table('client_accounts', function (Blueprint $table) {
                $table->string('portal_role', 32)->default('owner')->after('is_active');
            });

            try {
                Schema::table('client_accounts', function (Blueprint $table) {
                    $table->index('client_id');
                });
            } catch (\Throwable) {
                // فهرس client_id قد يكون موجوداً
            }

            try {
                Schema::table('client_accounts', function (Blueprint $table) {
                    $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
                });
            } catch (\Throwable) {
                // المفتاح الأجنبي قد يكون مضافاً
            }
        }
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
            $table->dropForeign(['client_id']);
        });

        Schema::table('client_accounts', function (Blueprint $table) {
            $indexes = Schema::getConnection()->getSchemaBuilder()->getIndexes('client_accounts');
            foreach ($indexes as $index) {
                $cols = $index['columns'] ?? [];
                if ($cols === ['client_id'] && ! ($index['unique'] ?? false) && ! ($index['primary'] ?? false)) {
                    $table->dropIndex($index['name']);
                    break;
                }
            }
        });

        Schema::table('client_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('client_accounts', 'portal_role')) {
                $table->dropColumn('portal_role');
            }
        });

        Schema::table('client_accounts', function (Blueprint $table) {
            $table->unique('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });
    }
};
