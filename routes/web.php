<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DepartmentManager\DepartmentManagerController;
use App\Http\Controllers\DepartmentManager\DepartmentManagerTaskController;
use App\Http\Controllers\DepartmentManager\DepartmentReportController as DepartmentManagerReportController;
use App\Http\Controllers\Admin\DepartmentReportController as AdminDepartmentReportController;
use App\Http\Controllers\Admin\DepartmentOversightController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QAController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialInvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\SystemMonitoringController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ClientSupportTicketController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\Admin\ClientAccountController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Support\ContactRequestController as SupportContactRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route مخصص لعرض الملفات من storage/app/public (صور، PDF، إلخ)
| يجب أن يكون قبل أي routes أخرى لتفادي اعتراض الطلبات
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', function ($path) {
    try {
        $path = str_replace('..', '', $path);
        $path = ltrim($path, '/');

        $basePath = storage_path('app/public');
        $filePath = $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);

        \Log::info('Storage route accessed', [
            'requested_path' => $path,
            'file_path' => $filePath,
            'file_exists' => @file_exists($filePath),
            'is_file' => @is_file($filePath),
            'storage_path' => $basePath,
        ]);

        if (!@file_exists($filePath)) {
            \Log::warning('Storage file not found', ['requested_path' => $path, 'file_path' => $filePath]);
            abort(404, 'File not found');
        }

        if (!@is_file($filePath)) {
            \Log::warning('Storage path is not a file', ['requested_path' => $path, 'file_path' => $filePath]);
            abort(404, 'Not a file');
        }

        $realPath = @realpath($filePath) ?: $filePath;
        $allowedPath = @realpath($basePath) ?: $basePath;
        $normalizedRealPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $realPath);
        $normalizedAllowedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $allowedPath);

        if (strpos($normalizedRealPath, $normalizedAllowedPath) !== 0) {
            \Log::warning('Storage access denied - path outside allowed directory', [
                'requested_path' => $path, 'file_path' => $filePath, 'real_path' => $realPath, 'allowed_path' => $allowedPath,
            ]);
            abort(404, 'Access denied');
        }

        if (!@is_readable($realPath)) {
            \Log::warning('Storage file is not readable', ['requested_path' => $path, 'real_path' => $realPath]);
            abort(403, 'File not readable');
        }

        $mimeType = @mime_content_type($realPath);
        if (!$mimeType) {
            $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];
            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }

        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ];
        if ($mimeType === 'application/pdf') {
            $headers['Content-Disposition'] = 'inline; filename="' . basename($realPath) . '"';
        }

        \Log::info('Storage file served successfully', [
            'requested_path' => $path, 'real_path' => $realPath, 'mime_type' => $mimeType,
        ]);

        return response()->file($realPath, $headers);
    } catch (\Exception $e) {
        \Log::error('Storage route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        abort(404, 'File not found');
    }
})->where('path', '.*')->name('storage.file')->middleware('web');

Route::get('/', [WebsiteController::class, 'home'])->name('website.home');
Route::get('/about', [WebsiteController::class, 'about'])->name('website.about');
Route::get('/services', [WebsiteController::class, 'services'])->name('website.services');
Route::get('/pricing', [WebsiteController::class, 'pricing'])->name('website.pricing');
Route::get('/academy', [WebsiteController::class, 'training'])->name('website.training');
Route::get('/academy/{training}', [WebsiteController::class, 'trainingShow'])->name('website.training.show');
Route::post('/academy/{training}/apply', [WebsiteController::class, 'trainingApply'])->name('website.training.apply');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('website.contact');
Route::post('/contact', [WebsiteController::class, 'submitContact'])->name('website.contact.submit');
Route::get('/case-studies', [WebsiteController::class, 'caseStudies'])->name('website.case-studies.index');
Route::get('/case-studies/{slug}', [WebsiteController::class, 'caseStudyShow'])->name('website.case-studies.show');

// =========================
// Client Auth (تسجيل دخول العملاء)
// =========================
Route::prefix('client')->name('client.')->group(function () {
    Route::get('login', [ClientAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [ClientAuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [ClientAuthController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'verified.code'])
    ->name('dashboard');

// Profile routes
Route::middleware(['auth', 'verified', 'verified.code'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete-picture');
});

// All authenticated routes
Route::middleware(['auth', 'verified', 'verified.code'])->group(function () {
    // =========================
    // Client Portal (بوابة العميل)
    // =========================
    // (تم نقل بوابة العميل إلى auth:client بدلاً من role:client)

    // Administration - Users
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');
    
    // Administration - Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:view-departments');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:create-departments');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:create-departments');
    Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:view-departments');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:edit-departments');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:edit-departments');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:delete-departments');
    
    // Login Activity Logs
    Route::get('login-activity', [LoginActivityController::class, 'index'])->name('login-activity.index')->middleware('permission:view-users|manage-roles');
    
    // System Monitoring
    Route::get('system-monitoring', [SystemMonitoringController::class, 'index'])->name('system-monitoring.index')->middleware('permission:view-users|manage-roles');
    
    // الأدوار والصلاحيات
    Route::prefix('roles')->name('roles.')->middleware('permission:manage-roles')->group(function () {
        Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('index');
        Route::get('/user/{user}/permissions', [App\Http\Controllers\RoleController::class, 'userPermissions'])->name('user-permissions');
        Route::post('/user/{user}/assign-role', [App\Http\Controllers\RoleController::class, 'assignRole'])->name('assign-role');
        Route::post('/user/{user}/assign-permissions', [App\Http\Controllers\RoleController::class, 'assignCustomPermissions'])->name('assign-permissions');
        Route::post('/role/{role}/update-permissions', [App\Http\Controllers\RoleController::class, 'updateRolePermissions'])->name('update-permissions');
    });
    
    // System Settings
    Route::prefix('system-settings')->name('system-settings.')->middleware('permission:view-settings')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::put('/update', [SystemSettingsController::class, 'update'])->name('update')->middleware('permission:edit-settings');
        Route::post('/reset', [SystemSettingsController::class, 'reset'])->name('reset')->middleware('permission:edit-settings');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:view-reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/employees', [ReportController::class, 'employees'])->name('employees')->middleware('permission:view-employees');
        Route::get('/employees/print', [ReportController::class, 'employeesPrint'])->name('employees.print')->middleware('permission:view-employees');
        Route::get('/projects', [ReportController::class, 'projects'])->name('projects')->middleware('permission:view-own-projects|view-all-projects');
        Route::get('/projects/print', [ReportController::class, 'projectsPrint'])->name('projects.print')->middleware('permission:view-own-projects|view-all-projects');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance')->middleware('permission:view-attendance');
        Route::get('/attendance/print', [ReportController::class, 'attendancePrint'])->name('attendance.print')->middleware('permission:view-attendance');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales')->middleware('permission:view-sales');
        Route::get('/sales/print', [ReportController::class, 'salesPrint'])->name('sales.print')->middleware('permission:view-sales');
        Route::get('/salaries', [ReportController::class, 'salaries'])->name('salaries')->middleware('permission:view-salaries');
        Route::get('/salaries/print', [ReportController::class, 'salariesPrint'])->name('salaries.print')->middleware('permission:view-salaries');
        Route::get('/tasks', [ReportController::class, 'tasks'])->name('tasks')->middleware('permission:view-own-tasks|view-all-tasks');
        Route::get('/tasks/print', [ReportController::class, 'tasksPrint'])->name('tasks.print')->middleware('permission:view-own-tasks|view-all-tasks');
        Route::get('/departments', [ReportController::class, 'departments'])->name('departments')->middleware('permission:view-departments');
        Route::get('/departments/print', [ReportController::class, 'departmentsPrint'])->name('departments.print')->middleware('permission:view-departments');
        Route::get('/performance', [ReportController::class, 'performance'])->name('performance');
    });
    
    // HR Department - Employees
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index')->middleware('permission:view-employees');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create')->middleware('permission:create-employees');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store')->middleware('permission:create-employees');
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show')->middleware('permission:view-employees');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit')->middleware('permission:edit-employees');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update')->middleware('permission:edit-employees');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy')->middleware('permission:delete-employees');
    
    // Attendance routes
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:view-attendance');
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create')->middleware('permission:create-attendance');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store')->middleware('permission:create-attendance');
    Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show')->middleware('permission:view-attendance');
    Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit')->middleware('permission:edit-attendance');
    Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update')->middleware('permission:edit-attendance');
    Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy')->middleware('permission:delete-attendance');
    Route::post('attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check-in')->middleware('permission:view-attendance');
    Route::post('attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out')->middleware('permission:view-attendance');
    Route::post('attendances/start-break', [AttendanceController::class, 'startBreak'])->name('attendances.start-break')->middleware('permission:view-attendance');
    Route::post('attendances/end-break', [AttendanceController::class, 'endBreak'])->name('attendances.end-break')->middleware('permission:view-attendance');
    Route::get('attendances/current-work-time', [AttendanceController::class, 'getCurrentWorkTime'])->name('attendances.current-work-time')->middleware('permission:view-attendance');
    
    // Leave routes
    Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index')->middleware('permission:view-leaves');
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create')->middleware('permission:create-leaves');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store')->middleware('permission:create-leaves');
    Route::get('leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show')->middleware('permission:view-leaves');
    Route::get('leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit')->middleware('permission:edit-leaves');
    Route::put('leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update')->middleware('permission:edit-leaves');
    Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy')->middleware('permission:delete-leaves');
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve')->middleware('permission:approve-leaves');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject')->middleware('permission:approve-leaves');
    
    // Salary routes
    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index')->middleware('permission:view-salaries');
    Route::get('salaries/create', [SalaryController::class, 'create'])->name('salaries.create')->middleware('permission:create-salaries');
    Route::post('salaries', [SalaryController::class, 'store'])->name('salaries.store')->middleware('permission:create-salaries');
    Route::get('salaries/{salary}', [SalaryController::class, 'show'])->name('salaries.show')->middleware('permission:view-salaries');
    Route::get('salaries/{salary}/edit', [SalaryController::class, 'edit'])->name('salaries.edit')->middleware('permission:edit-salaries');
    Route::put('salaries/{salary}', [SalaryController::class, 'update'])->name('salaries.update')->middleware('permission:edit-salaries');
    Route::delete('salaries/{salary}', [SalaryController::class, 'destroy'])->name('salaries.destroy')->middleware('permission:delete-salaries');
    Route::post('salaries/generate', [SalaryController::class, 'generateSalaries'])->name('salaries.generate')->middleware('permission:edit-salaries');
    Route::post('salaries/{salary}/approve', [SalaryController::class, 'approve'])->name('salaries.approve')->middleware('permission:approve-salaries');
    Route::post('salaries/{salary}/mark-paid', [SalaryController::class, 'markAsPaid'])->name('salaries.mark-paid')->middleware('permission:edit-salaries');
    
    // Project Management
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index')->middleware('permission:view-own-projects|view-all-projects');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create')->middleware('permission:create-projects');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store')->middleware('permission:create-projects');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show')->middleware('permission:view-own-projects|view-all-projects');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit')->middleware('permission:edit-projects');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update')->middleware('permission:edit-projects');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy')->middleware('permission:delete-projects');
    
    // Tasks
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index')->middleware('permission:view-own-tasks|view-all-tasks');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('permission:create-tasks');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('permission:create-tasks');
    Route::get('tasks/project-members', [TaskController::class, 'getProjectMembersAjax'])->name('tasks.project-members')->middleware('permission:create-tasks|edit-tasks');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show')->middleware('permission:view-own-tasks|view-all-tasks');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->middleware('permission:edit-tasks');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update')->middleware('permission:edit-tasks');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('permission:delete-tasks');
    Route::post('tasks/{task}/updates', [TaskController::class, 'addUpdate'])->name('tasks.updates.store')->middleware('permission:view-own-tasks|view-all-tasks');

    // Department manager workspace
    Route::prefix('department-manager')->name('department-manager.')->middleware('department.manager')->group(function () {
        Route::get('/', [DepartmentManagerController::class, 'dashboard'])->name('dashboard');
        Route::get('/tasks/create', [DepartmentManagerTaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [DepartmentManagerTaskController::class, 'store'])->name('tasks.store');

        Route::get('/reports', [DepartmentManagerReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [DepartmentManagerReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [DepartmentManagerReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{departmentReport}', [DepartmentManagerReportController::class, 'show'])->name('reports.show');
    });

    // Admin oversight: Department reports
    Route::get('admin/department-reports', [AdminDepartmentReportController::class, 'index'])
        ->name('admin.department-reports.index')
        ->middleware('permission:view-reports');
    Route::get('admin/department-reports/{departmentReport}', [AdminDepartmentReportController::class, 'show'])
        ->name('admin.department-reports.show')
        ->middleware('permission:view-reports');

    Route::get('admin/department-oversight', [DepartmentOversightController::class, 'index'])
        ->name('admin.department-oversight.index')
        ->middleware('permission:view-reports|view-departments');
    
    // Development - Bugs
    Route::get('bugs', [BugController::class, 'index'])->name('bugs.index')->middleware('permission:view-bugs');
    Route::get('bugs/create', [BugController::class, 'create'])->name('bugs.create')->middleware('permission:create-bugs');
    Route::post('bugs', [BugController::class, 'store'])->name('bugs.store')->middleware('permission:create-bugs');
    Route::get('bugs/{bug}', [BugController::class, 'show'])->name('bugs.show')->middleware('permission:view-bugs');
    Route::get('bugs/{bug}/edit', [BugController::class, 'edit'])->name('bugs.edit')->middleware('permission:edit-bugs');
    Route::put('bugs/{bug}', [BugController::class, 'update'])->name('bugs.update')->middleware('permission:edit-bugs');
    Route::delete('bugs/{bug}', [BugController::class, 'destroy'])->name('bugs.destroy')->middleware('permission:delete-bugs');
    
    // Development - QA
    Route::get('qa', [QAController::class, 'index'])->name('qa.index')->middleware('permission:view-qa');
    Route::get('qa/create', [QAController::class, 'create'])->name('qa.create')->middleware('permission:create-qa');
    Route::post('qa', [QAController::class, 'store'])->name('qa.store')->middleware('permission:create-qa');
    Route::get('qa/{qa}', [QAController::class, 'show'])->name('qa.show')->middleware('permission:view-qa');
    Route::get('qa/{qa}/edit', [QAController::class, 'edit'])->name('qa.edit')->middleware('permission:edit-qa');
    Route::put('qa/{qa}', [QAController::class, 'update'])->name('qa.update')->middleware('permission:edit-qa');
    Route::delete('qa/{qa}', [QAController::class, 'destroy'])->name('qa.destroy')->middleware('permission:delete-qa');
    Route::post('qa/{qa}/execute', [QAController::class, 'execute'])->name('qa.execute')->middleware('permission:edit-qa');
    
    // Sales & Marketing - Clients
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index')->middleware('permission:view-clients');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create')->middleware('permission:create-clients');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store')->middleware('permission:create-clients');
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show')->middleware('permission:view-clients');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit')->middleware('permission:edit-clients');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update')->middleware('permission:edit-clients');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy')->middleware('permission:delete-clients');

    // Client Accounts (بوابة العملاء) - داخل لوحة الأدمن
    Route::prefix('client-accounts')->name('client-accounts.')->middleware('permission:view-clients')->group(function () {
        Route::get('/', [ClientAccountController::class, 'index'])->name('index');
        Route::get('/create', [ClientAccountController::class, 'create'])->name('create')->middleware('permission:create-clients');
        Route::post('/', [ClientAccountController::class, 'store'])->name('store')->middleware('permission:create-clients');
        Route::get('/{clientAccount}/edit', [ClientAccountController::class, 'edit'])->name('edit')->middleware('permission:edit-clients');
        Route::put('/{clientAccount}', [ClientAccountController::class, 'update'])->name('update')->middleware('permission:edit-clients');
        Route::delete('/{clientAccount}', [ClientAccountController::class, 'destroy'])->name('destroy')->middleware('permission:delete-clients');
    });
    
    // Sales & Marketing - Sales
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index')->middleware('permission:view-sales');
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create')->middleware('permission:create-sales');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store')->middleware('permission:create-sales');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show')->middleware('permission:view-sales');
    Route::get('sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit')->middleware('permission:edit-sales');
    Route::put('sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:edit-sales');
    Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('permission:delete-sales');
    Route::post('sales/{sale}/update-stage', [SaleController::class, 'updateStage'])->name('sales.update-stage')->middleware('permission:edit-sales');
    Route::post('sales/{sale}/generate-invoice', [SaleController::class, 'generateInvoice'])->name('sales.generate-invoice')->middleware('permission:edit-sales');
    Route::get('sales/statistics/data', [SaleController::class, 'getStatistics'])->name('sales.statistics')->middleware('permission:view-sales');
    
    // Support - Tickets
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index')->middleware('permission:view-tickets');
    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create')->middleware('permission:create-tickets');
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store')->middleware('permission:create-tickets');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show')->middleware('permission:view-tickets');
    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit')->middleware('permission:edit-tickets');
    Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update')->middleware('permission:edit-tickets');
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy')->middleware('permission:delete-tickets');

    // Support - Contact requests / Consultation bookings
    Route::get('support/contact-requests', [SupportContactRequestController::class, 'index'])->name('support.contact-requests.index')->middleware('permission:view-tickets');
    Route::get('support/contact-requests/{contactRequest}', [SupportContactRequestController::class, 'show'])->name('support.contact-requests.show')->middleware('permission:view-tickets');
    Route::patch('support/contact-requests/{contactRequest}/status', [SupportContactRequestController::class, 'updateStatus'])->name('support.contact-requests.status')->middleware('permission:edit-tickets');
    
    // Finance & Accounting
    Route::prefix('accounting')->name('accounting.')->middleware('permission:view-finance')->group(function () {
        // لوحة التحكم المالية
        Route::get('/', [AccountingController::class, 'index'])->name('index');
        
        // إدارة الحسابات
        Route::get('/accounts', [AccountingController::class, 'accounts'])->name('accounts');
        Route::post('/accounts', [AccountingController::class, 'createAccount'])->name('accounts.create')->middleware('permission:edit-finance');
        Route::get('/accounts/{account}/edit', [AccountingController::class, 'editAccount'])->name('accounts.edit')->middleware('permission:edit-finance');
        Route::put('/accounts/{account}', [AccountingController::class, 'updateAccount'])->name('accounts.update')->middleware('permission:edit-finance');
        Route::delete('/accounts/{account}', [AccountingController::class, 'deleteAccount'])->name('accounts.delete')->middleware('permission:edit-finance');
        
        // القيود المحاسبية
        Route::get('/journal-entries', [JournalEntryController::class, 'index'])->name('journal-entries');
        Route::get('/journal-entries/create', [JournalEntryController::class, 'create'])->name('journal-entries.create')->middleware('permission:edit-finance');
        Route::post('/journal-entries', [JournalEntryController::class, 'store'])->name('journal-entries.store')->middleware('permission:edit-finance');
        Route::get('/journal-entries/{journalEntry}', [JournalEntryController::class, 'show'])->name('journal-entries.show');
        Route::get('/journal-entries/{journalEntry}/edit', [JournalEntryController::class, 'edit'])->name('journal-entries.edit')->middleware('permission:edit-finance');
        Route::put('/journal-entries/{journalEntry}', [JournalEntryController::class, 'update'])->name('journal-entries.update')->middleware('permission:edit-finance');
        Route::delete('/journal-entries/{journalEntry}', [JournalEntryController::class, 'destroy'])->name('journal-entries.destroy')->middleware('permission:edit-finance');
        Route::post('/journal-entries/{journalEntry}/approve', [JournalEntryController::class, 'approve'])->name('journal-entries.approve')->middleware('permission:edit-finance');
        Route::post('/journal-entries/{journalEntry}/post', [JournalEntryController::class, 'post'])->name('journal-entries.post')->middleware('permission:edit-finance');
        
        // التقارير المالية
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index'])->name('index');
            Route::get('/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('income-statement');
            Route::get('/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('cash-flow');
            Route::get('/trial-balance', [FinancialReportController::class, 'trialBalance'])->name('trial-balance');
        });
    });
    
    // الفواتير والمدفوعات والمصروفات - Financial Invoices
    Route::get('financial-invoices', [FinancialInvoiceController::class, 'index'])->name('financial-invoices.index')->middleware('permission:view-finance');
    Route::get('financial-invoices/create', [FinancialInvoiceController::class, 'create'])->name('financial-invoices.create')->middleware('permission:create-finance');
    Route::post('financial-invoices', [FinancialInvoiceController::class, 'store'])->name('financial-invoices.store')->middleware('permission:create-finance');
    Route::get('financial-invoices/{financialInvoice}', [FinancialInvoiceController::class, 'show'])->name('financial-invoices.show')->middleware('permission:view-finance');
    Route::get('financial-invoices/{financialInvoice}/edit', [FinancialInvoiceController::class, 'edit'])->name('financial-invoices.edit')->middleware('permission:edit-finance');
    Route::put('financial-invoices/{financialInvoice}', [FinancialInvoiceController::class, 'update'])->name('financial-invoices.update')->middleware('permission:edit-finance');
    Route::delete('financial-invoices/{financialInvoice}', [FinancialInvoiceController::class, 'destroy'])->name('financial-invoices.destroy')->middleware('permission:delete-finance');
    Route::post('financial-invoices/{invoice}/mark-as-sent', [FinancialInvoiceController::class, 'markAsSent'])->name('financial-invoices.mark-as-sent')->middleware('permission:edit-finance');
    Route::post('financial-invoices/{invoice}/mark-as-paid', [FinancialInvoiceController::class, 'markAsPaid'])->name('financial-invoices.mark-as-paid')->middleware('permission:edit-finance');
    
    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index')->middleware('permission:view-finance');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create')->middleware('permission:create-finance');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store')->middleware('permission:create-finance');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show')->middleware('permission:view-finance');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit')->middleware('permission:edit-finance');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update')->middleware('permission:edit-finance');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy')->middleware('permission:delete-finance');
    Route::post('payments/{payment}/mark-as-completed', [PaymentController::class, 'markAsCompleted'])->name('payments.mark-as-completed')->middleware('permission:edit-finance');
    
    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index')->middleware('permission:view-finance');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create')->middleware('permission:create-finance');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store')->middleware('permission:create-finance');
    Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show')->middleware('permission:view-finance');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('permission:edit-finance');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update')->middleware('permission:edit-finance');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('permission:delete-finance');
    
    // Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index')->middleware('permission:view-invoices');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create')->middleware('permission:create-invoices');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store')->middleware('permission:create-invoices');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show')->middleware('permission:view-invoices');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit')->middleware('permission:edit-invoices');
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update')->middleware('permission:edit-invoices');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy')->middleware('permission:delete-invoices');
    Route::post('invoices/{invoice}/mark-as-sent', [InvoiceController::class, 'markAsSent'])->name('invoices.mark-as-sent')->middleware('permission:edit-invoices');
    Route::post('invoices/{invoice}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-as-paid')->middleware('permission:edit-invoices');
    
    // Legal - Contracts
    Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index')->middleware('permission:view-contracts');
    Route::get('contracts/create', [ContractController::class, 'create'])->name('contracts.create')->middleware('permission:create-contracts');
    Route::post('contracts', [ContractController::class, 'store'])->name('contracts.store')->middleware('permission:create-contracts');
    Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show')->middleware('permission:view-contracts');
    Route::get('contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit')->middleware('permission:edit-contracts');
    Route::put('contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update')->middleware('permission:edit-contracts');
    Route::delete('contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy')->middleware('permission:delete-contracts');
    Route::post('contracts/{contract}/generate-invoice', [ContractController::class, 'generateInvoice'])->name('contracts.generate-invoice')->middleware('permission:edit-contracts');
    Route::post('contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew')->middleware('permission:edit-contracts');
    Route::get('contracts/statistics/data', [ContractController::class, 'getStatistics'])->name('contracts.statistics')->middleware('permission:view-contracts');
    
    // Notifications routes
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Messages routes
    // Messages Routes
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('messages/{message}/mark-important', [MessageController::class, 'markAsImportant'])->name('messages.mark-important');
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::get('messages/recent', [MessageController::class, 'getRecentMessages'])->name('messages.recent');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    
    // Design & Marketing
    Route::get('design', [DesignController::class, 'index'])->name('design.index')->middleware('permission:view-design');
    Route::get('marketing', [MarketingController::class, 'index'])->name('marketing.index')->middleware('permission:view-marketing');
    
    // Training & Development
    Route::get('training', [TrainingController::class, 'index'])->name('training.index')->middleware('permission:view-training');
    Route::get('training/create', [TrainingController::class, 'create'])->name('training.create')->middleware('permission:create-training');
    Route::post('training/seed-demos', [TrainingController::class, 'storeDemo'])->name('training.seed-demos')->middleware('permission:create-training');
    Route::post('training', [TrainingController::class, 'store'])->name('training.store')->middleware('permission:create-training');
    Route::get('training/{training}', [TrainingController::class, 'show'])->name('training.show')->middleware('permission:view-training');
    Route::get('training/{training}/edit', [TrainingController::class, 'edit'])->name('training.edit')->middleware('permission:edit-training');
    Route::put('training/{training}', [TrainingController::class, 'update'])->name('training.update')->middleware('permission:edit-training');
    Route::delete('training/{training}', [TrainingController::class, 'destroy'])->name('training.destroy')->middleware('permission:delete-training');
    Route::post('training/{training}/participants', [TrainingController::class, 'addParticipant'])->name('training.add-participant')->middleware('permission:edit-training');
    Route::delete('training/{training}/participants/{participant}', [TrainingController::class, 'removeParticipant'])->name('training.remove-participant')->middleware('permission:edit-training');
    Route::get('training/{training}/applications', [TrainingController::class, 'applications'])->name('training.applications')->middleware('permission:view-training');
    Route::patch('training/applications/{application}/status', [TrainingController::class, 'updateApplicationStatus'])->name('training.applications.status')->middleware('permission:edit-training');
    
    // Meetings & Conferences
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index')->middleware('permission:view-meetings');
    Route::get('meetings/create', [MeetingController::class, 'create'])->name('meetings.create')->middleware('permission:create-meetings');
    Route::post('meetings', [MeetingController::class, 'store'])->name('meetings.store')->middleware('permission:create-meetings');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show')->middleware('permission:view-meetings');
    Route::get('meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit')->middleware('permission:edit-meetings');
    Route::put('meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update')->middleware('permission:edit-meetings');
    Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy')->middleware('permission:delete-meetings');
    Route::post('meetings/{meeting}/participants', [MeetingController::class, 'addParticipant'])->name('meetings.add-participant')->middleware('permission:edit-meetings');
    Route::delete('meetings/{meeting}/participants/{participant}', [MeetingController::class, 'removeParticipant'])->name('meetings.remove-participant')->middleware('permission:edit-meetings');
    
    // Assets & Properties
    Route::get('assets', function () {
        return view('assets.index');
    })->name('assets.index')->middleware('permission:view-assets');
});

// =========================
// Client Portal (بوابة العميل) - Guard مستقل
// =========================
Route::prefix('client')->name('client.')->middleware('auth:client')->group(function () {
    Route::get('/', [ClientPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [ClientPortalController::class, 'projects'])->name('projects');
    Route::get('/invoices', [ClientPortalController::class, 'invoices'])->name('invoices');

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [ClientSupportTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/create', [ClientSupportTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [ClientSupportTicketController::class, 'store'])->name('tickets.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';