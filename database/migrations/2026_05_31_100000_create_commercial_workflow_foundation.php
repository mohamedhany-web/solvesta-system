<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->enum('source', ['ads', 'social_media', 'referral', 'website', 'bd_outreach', 'event', 'other'])->default('website');
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost', 'on_hold'])->default('new');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('service_interest')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('contact_request_id')->nullable()->unique();
            $table->foreignId('converted_client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('converted_sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->string('lost_reason')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('contact_requests')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->foreign('contact_request_id')->references('id')->on('contact_requests')->nullOnDelete();
            });
        }

        Schema::table('sales', function (Blueprint $table) {
            if (! Schema::hasColumn('sales', 'lead_id')) {
                $table->foreignId('lead_id')->nullable()->after('id')->constrained('leads')->nullOnDelete();
            }
            if (! Schema::hasColumn('sales', 'requirement_summary')) {
                $table->text('requirement_summary')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('sales', 'qualification_status')) {
                $table->enum('qualification_status', ['pending', 'qualified', 'disqualified'])->default('pending')->after('stage');
            }
            if (! Schema::hasColumn('sales', 'lost_reason')) {
                $table->string('lost_reason')->nullable()->after('qualification_status');
            }
        });

        Schema::table('contracts', function (Blueprint $table) {
            if (! Schema::hasColumn('contracts', 'sale_id')) {
                $table->foreignId('sale_id')->nullable()->after('client_id')->constrained('sales')->nullOnDelete();
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'contract_id')) {
                $table->foreignId('contract_id')->nullable()->after('client_id')->constrained('contracts')->nullOnDelete();
            }
            if (! Schema::hasColumn('projects', 'sale_id')) {
                $table->foreignId('sale_id')->nullable()->after('contract_id')->constrained('sales')->nullOnDelete();
            }
            if (! Schema::hasColumn('projects', 'kickoff_status')) {
                $table->enum('kickoff_status', ['blocked_pending_payment', 'ready', 'started'])->default('ready')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'kickoff_status')) {
                $table->dropColumn('kickoff_status');
            }
            if (Schema::hasColumn('projects', 'sale_id')) {
                $table->dropConstrainedForeignId('sale_id');
            }
            if (Schema::hasColumn('projects', 'contract_id')) {
                $table->dropConstrainedForeignId('contract_id');
            }
        });

        Schema::table('contracts', function (Blueprint $table) {
            if (Schema::hasColumn('contracts', 'sale_id')) {
                $table->dropConstrainedForeignId('sale_id');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
            foreach (['lost_reason', 'qualification_status', 'requirement_summary', 'lead_id'] as $col) {
                if (Schema::hasColumn('sales', $col)) {
                    if ($col === 'lead_id') {
                        $table->dropConstrainedForeignId('lead_id');
                    } else {
                        $table->dropColumn($col);
                    }
                }
            }
        });

        Schema::dropIfExists('leads');
    }
};
