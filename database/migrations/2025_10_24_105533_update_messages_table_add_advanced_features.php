<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Rename recipient_id to receiver_id
            $table->renameColumn('recipient_id', 'receiver_id');
            
            // Rename message to body
            $table->renameColumn('message', 'body');
            
            // Make subject nullable
            $table->string('subject')->nullable()->change();
            
            // Add new columns
            $table->enum('type', ['direct', 'group', 'announcement'])->default('direct')->after('subject');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->after('type');
            $table->boolean('is_important')->default(false)->after('is_read');
            $table->boolean('is_deleted_by_sender')->default(false)->after('is_important');
            $table->boolean('is_deleted_by_receiver')->default(false)->after('is_deleted_by_sender');
            $table->json('attachments')->nullable()->after('is_deleted_by_receiver');
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('cascade')->after('attachments');
            
            // Update indexes
            $table->dropIndex(['recipient_id', 'is_read']);
            $table->index(['receiver_id', 'is_read', 'created_at']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'type',
                'priority', 
                'is_important',
                'is_deleted_by_sender',
                'is_deleted_by_receiver',
                'attachments',
                'parent_message_id'
            ]);
            
            // Rename back
            $table->renameColumn('receiver_id', 'recipient_id');
            $table->renameColumn('body', 'message');
            
            // Make subject not nullable
            $table->string('subject')->nullable(false)->change();
            
            // Restore indexes
            $table->dropIndex(['receiver_id', 'is_read', 'created_at']);
            $table->dropIndex(['type', 'created_at']);
            $table->index(['recipient_id', 'is_read']);
        });
    }
};