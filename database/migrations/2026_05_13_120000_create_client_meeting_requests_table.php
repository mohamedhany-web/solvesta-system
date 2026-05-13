<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_meeting_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->dateTime('preferred_at');
            $table->text('alternative_times')->nullable();
            $table->unsignedTinyInteger('participants_count')->nullable();
            $table->string('meeting_format', 24)->default('either'); // online, in_person, either
            $table->string('status', 32)->default('pending'); // pending, confirmed, declined, completed, cancelled
            $table->dateTime('scheduled_at')->nullable();
            $table->string('meeting_link', 2000)->nullable();
            $table->text('location_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('response_message')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_meeting_requests');
    }
};
