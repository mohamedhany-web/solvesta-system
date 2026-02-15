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
        $driverName = Schema::getConnection()->getDriverName();
        
        if ($driverName === 'sqlite') {
            // For SQLite, we need to check and add columns differently
            if (!$this->columnExists('attendances', 'break_duration_minutes')) {
                DB::statement('ALTER TABLE attendances ADD COLUMN break_duration_minutes INTEGER NULL');
            }
            
            if (!$this->columnExists('attendances', 'current_status')) {
                DB::statement("ALTER TABLE attendances ADD COLUMN current_status VARCHAR(50) NULL");
            }
        } else {
            // For MySQL/PostgreSQL, use normal Schema builder
            Schema::table('attendances', function (Blueprint $table) {
                if (!$this->columnExists('attendances', 'break_duration_minutes')) {
                    $table->integer('break_duration_minutes')->nullable()->after('break_end');
                }
                
                if (!$this->columnExists('attendances', 'current_status')) {
                    $table->enum('current_status', ['working', 'on_break', 'completed'])->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if ($this->columnExists('attendances', 'break_duration_minutes')) {
                $table->dropColumn('break_duration_minutes');
            }
            
            if ($this->columnExists('attendances', 'current_status')) {
                $table->dropColumn('current_status');
            }
        });
    }
    
    /**
     * Check if column exists in table (works for both SQLite and MySQL)
     */
    private function columnExists(string $table, string $column): bool
    {
        $connection = Schema::getConnection();
        $driverName = $connection->getDriverName();
        
        if ($driverName === 'sqlite') {
            // For SQLite, check schema
            $columns = DB::select("PRAGMA table_info({$table})");
            foreach ($columns as $col) {
                if ($col->name === $column) {
                    return true;
                }
            }
            return false;
        } else {
            // For MySQL/PostgreSQL
            return Schema::hasColumn($table, $column);
        }
    }
};
