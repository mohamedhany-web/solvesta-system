<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_messages', function (Blueprint $table) {
            $table->id();
            $table->string('imap_uid')->nullable()->index();
            $table->string('message_id')->nullable()->index();
            $table->string('folder', 64)->default('INBOX');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('to_email')->nullable();
            $table->text('cc')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('forwarded')->default(false);
            $table->timestamp('forwarded_at')->nullable();
            $table->foreignId('in_reply_to_id')->nullable()->constrained('mail_messages')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['folder', 'imap_uid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_messages');
    }
};
