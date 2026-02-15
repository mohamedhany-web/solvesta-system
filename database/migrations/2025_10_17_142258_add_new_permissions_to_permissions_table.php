<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // CRM Permissions
        $crmPermissions = [
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            'view-sales',
            'create-sales',
            'edit-sales',
            'delete-sales',
            'view-contracts',
            'create-contracts',
            'edit-contracts',
            'delete-contracts',
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
        ];

        // Advanced HR Permissions
        $hrPermissions = [
            'view-training',
            'create-training',
            'edit-training',
            'delete-training',
            'view-meetings',
            'create-meetings',
            'edit-meetings',
            'delete-meetings',
            'view-assets',
            'create-assets',
            'edit-assets',
            'delete-assets',
            'manage-asset-maintenance',
        ];

        // System Administration Permissions
        $systemPermissions = [
            'view-reports',
            'export-data',
            'import-data',
            'backup-data',
            'restore-data',
        ];

        $allPermissions = array_merge($crmPermissions, $hrPermissions, $systemPermissions);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // CRM Permissions
        $crmPermissions = [
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            'view-sales',
            'create-sales',
            'edit-sales',
            'delete-sales',
            'view-contracts',
            'create-contracts',
            'edit-contracts',
            'delete-contracts',
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
        ];

        // Advanced HR Permissions
        $hrPermissions = [
            'view-training',
            'create-training',
            'edit-training',
            'delete-training',
            'view-meetings',
            'create-meetings',
            'edit-meetings',
            'delete-meetings',
            'view-assets',
            'create-assets',
            'edit-assets',
            'delete-assets',
            'manage-asset-maintenance',
        ];

        // System Administration Permissions
        $systemPermissions = [
            'view-reports',
            'export-data',
            'import-data',
            'backup-data',
            'restore-data',
        ];

        $allPermissions = array_merge($crmPermissions, $hrPermissions, $systemPermissions);

        foreach ($allPermissions as $permission) {
            Permission::where('name', $permission)->delete();
        }
    }
};