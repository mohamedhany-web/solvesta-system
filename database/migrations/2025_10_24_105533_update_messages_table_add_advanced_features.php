<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make migration resilient across environments (some DBs already renamed columns)
        $columns = Schema::getColumnListing('messages');
        $has = fn (string $col) => in_array($col, $columns, true);

        // 1) Rename columns (only when source exists and target doesn't)
        Schema::table('messages', function (Blueprint $table) use ($has) {
            if ($has('recipient_id') && !$has('receiver_id')) {
                $table->renameColumn('recipient_id', 'receiver_id');
            }
            if ($has('message') && !$has('body')) {
                $table->renameColumn('message', 'body');
            }
        });

        // Refresh column list after renames
        $columns = Schema::getColumnListing('messages');
        $has = fn (string $col) => in_array($col, $columns, true);

        // 2) Add/change columns guarded
        Schema::table('messages', function (Blueprint $table) use ($has) {
            if ($has('subject')) {
                $table->string('subject')->nullable()->change();
            }

            if (!$has('type')) {
                $table->enum('type', ['direct', 'group', 'announcement'])->default('direct')->after('subject');
            }
            if (!$has('priority')) {
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->after('type');
            }
            if (!$has('is_important')) {
                $table->boolean('is_important')->default(false)->after('is_read');
            }
            if (!$has('is_deleted_by_sender')) {
                $table->boolean('is_deleted_by_sender')->default(false)->after('is_important');
            }
            if (!$has('is_deleted_by_receiver')) {
                $table->boolean('is_deleted_by_receiver')->default(false)->after('is_deleted_by_sender');
            }
            if (!$has('attachments')) {
                $table->json('attachments')->nullable()->after('is_deleted_by_receiver');
            }
            if (!$has('parent_message_id')) {
                $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('cascade')->after('attachments');
            }
        });

        // 3) Index updates (best-effort; names differ across DBs)
        // Drop legacy index if present then ensure new indexes exist.
        try {
            $indexNames = collect(DB::select('SHOW INDEX FROM `messages`'))
                ->pluck('Key_name')
                ->unique()
                ->values()
                ->all();

            $hasIndex = fn (string $name) => in_array($name, $indexNames, true);

            Schema::table('messages', function (Blueprint $table) use ($hasIndex, $has) {
                // Legacy index name is typically: messages_recipient_id_is_read_index
                if ($hasIndex('messages_recipient_id_is_read_index')) {
                    $table->dropIndex('messages_recipient_id_is_read_index');
                }

                if ($has('receiver_id') && !$hasIndex('messages_receiver_id_is_read_created_at_index')) {
                    $table->index(['receiver_id', 'is_read', 'created_at']);
                }
                if (!$hasIndex('messages_type_created_at_index')) {
                    $table->index(['type', 'created_at']);
                }
            });
        } catch (\Throwable $e) {
            // If SHOW INDEX isn't supported, ignore index management here.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = Schema::getColumnListing('messages');
        $has = fn (string $col) => in_array($col, $columns, true);

        Schema::table('messages', function (Blueprint $table) use ($has) {
            $drop = [];
            foreach ([
                'type',
                'priority',
                'is_important',
                'is_deleted_by_sender',
                'is_deleted_by_receiver',
                'attachments',
                'parent_message_id',
            ] as $col) {
                if ($has($col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });

        // Rename back (guarded)
        $columns = Schema::getColumnListing('messages');
        $has = fn (string $col) => in_array($col, $columns, true);

        Schema::table('messages', function (Blueprint $table) use ($has) {
            if ($has('receiver_id') && !$has('recipient_id')) {
                $table->renameColumn('receiver_id', 'recipient_id');
            }
            if ($has('body') && !$has('message')) {
                $table->renameColumn('body', 'message');
            }
        });

        // Index cleanup (best-effort)
        try {
            $indexNames = collect(DB::select('SHOW INDEX FROM `messages`'))
                ->pluck('Key_name')
                ->unique()
                ->values()
                ->all();
            $hasIndex = fn (string $name) => in_array($name, $indexNames, true);

            Schema::table('messages', function (Blueprint $table) use ($hasIndex, $has) {
                if ($hasIndex('messages_receiver_id_is_read_created_at_index')) {
                    $table->dropIndex('messages_receiver_id_is_read_created_at_index');
                }
                if ($hasIndex('messages_type_created_at_index')) {
                    $table->dropIndex('messages_type_created_at_index');
                }

                if ($has('recipient_id') && !$hasIndex('messages_recipient_id_is_read_index')) {
                    $table->index(['recipient_id', 'is_read']);
                }
            });
        } catch (\Throwable $e) {
        }
    }
};