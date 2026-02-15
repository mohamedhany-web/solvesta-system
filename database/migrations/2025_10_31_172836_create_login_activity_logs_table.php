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
        Schema::create('login_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('activity_type', ['login', 'verification_code_sent', 'verification_code_verified', 'verification_code_resend', 'logout'])->default('login');
            $table->string('verification_code')->nullable(); // الكود المرسل (مخفي جزئياً)
            $table->string('email')->nullable(); // البريد الإلكتروني الذي تم الإرسال إليه
            $table->enum('status', ['success', 'failed', 'pending'])->default('success');
            $table->text('message')->nullable(); // رسالة إضافية
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('activity_at')->useCurrent(); // وقت النشاط
            $table->timestamps();
            
            $table->index(['user_id', 'activity_type']);
            $table->index('activity_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_activity_logs');
    }
};
