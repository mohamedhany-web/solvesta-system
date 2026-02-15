<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserEmployeeSeeder extends Seeder
{
    public function run()
    {
        // إنشاء Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@solvesta.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@solvesta.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // إنشاء موظف Super Admin
        Employee::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'employee_id' => 'EMP001',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@solvesta.com',
                'phone' => '+966501234567',
                'hire_date' => now()->subYears(2),
                'salary' => 50000,
                'department_id' => 1, // الإدارة العليا
                'position' => 'System Administrator',
                'employment_type' => 'full_time',
                'status' => 'active',
                'user_id' => $superAdmin->id,
            ]
        );

        // إنشاء C-Suite
        $cSuiteUsers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ceo@solvesta.com',
                'role' => 'admin',
                'employee_data' => [
                    'employee_id' => 'EMP002',
                    'first_name' => 'أحمد',
                    'last_name' => 'محمد',
                    'phone' => '+966501234568',
                    'salary' => 45000,
                    'position' => 'Chief Executive Officer',
                    'department_id' => 1,
                ]
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'cto@solvesta.com',
                'role' => 'admin',
                'employee_data' => [
                    'employee_id' => 'EMP003',
                    'first_name' => 'فاطمة',
                    'last_name' => 'علي',
                    'phone' => '+966501234569',
                    'salary' => 42000,
                    'position' => 'Chief Technology Officer',
                    'department_id' => 1,
                ]
            ],
            [
                'name' => 'خالد أحمد',
                'email' => 'cfo@solvesta.com',
                'role' => 'accountant',
                'employee_data' => [
                    'employee_id' => 'EMP004',
                    'first_name' => 'خالد',
                    'last_name' => 'أحمد',
                    'phone' => '+966501234570',
                    'salary' => 40000,
                    'position' => 'Chief Financial Officer',
                    'department_id' => 1,
                ]
            ],
            [
                'name' => 'سارة حسن',
                'email' => 'coo@solvesta.com',
                'role' => 'admin',
                'employee_data' => [
                    'employee_id' => 'EMP005',
                    'first_name' => 'سارة',
                    'last_name' => 'حسن',
                    'phone' => '+966501234571',
                    'salary' => 38000,
                    'position' => 'Chief Operating Officer',
                    'department_id' => 1,
                ]
            ],
        ];

        foreach ($cSuiteUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($userData['employee_data'], [
                    'email' => $userData['email'],
                    'hire_date' => now()->subMonths(rand(6, 24)),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'user_id' => $user->id,
                ])
            );
        }

        // إنشاء المدراء
        $managers = [
            [
                'name' => 'محمد عبدالله',
                'email' => 'hr.manager@solvesta.com',
                'role' => 'hr',
                'employee_data' => [
                    'employee_id' => 'EMP006',
                    'first_name' => 'محمد',
                    'last_name' => 'عبدالله',
                    'phone' => '+966501234572',
                    'salary' => 25000,
                    'position' => 'HR Manager',
                    'department_id' => 2, // الموارد البشرية
                ]
            ],
            [
                'name' => 'نورا سالم',
                'email' => 'project.manager@solvesta.com',
                'role' => 'project_manager',
                'employee_data' => [
                    'employee_id' => 'EMP007',
                    'first_name' => 'نورا',
                    'last_name' => 'سالم',
                    'phone' => '+966501234573',
                    'salary' => 28000,
                    'position' => 'Project Manager',
                    'department_id' => 3, // إدارة المشاريع
                ]
            ],
            [
                'name' => 'عبدالرحمن يوسف',
                'email' => 'dev.manager@solvesta.com',
                'role' => 'developer',
                'employee_data' => [
                    'employee_id' => 'EMP008',
                    'first_name' => 'عبدالرحمن',
                    'last_name' => 'يوسف',
                    'phone' => '+966501234574',
                    'salary' => 32000,
                    'position' => 'Development Manager',
                    'department_id' => 4, // التطوير
                ]
            ],
            [
                'name' => 'مريم أحمد',
                'email' => 'sales.manager@solvesta.com',
                'role' => 'sales_rep',
                'employee_data' => [
                    'employee_id' => 'EMP009',
                    'first_name' => 'مريم',
                    'last_name' => 'أحمد',
                    'phone' => '+966501234575',
                    'salary' => 26000,
                    'position' => 'Sales Manager',
                    'department_id' => 7, // المبيعات
                ]
            ],
            [
                'name' => 'يوسف محمد',
                'email' => 'support.manager@solvesta.com',
                'role' => 'support',
                'employee_data' => [
                    'employee_id' => 'EMP010',
                    'first_name' => 'يوسف',
                    'last_name' => 'محمد',
                    'phone' => '+966501234576',
                    'salary' => 22000,
                    'position' => 'Support Manager',
                    'department_id' => 6, // الدعم الفني
                ]
            ],
            [
                'name' => 'هند علي',
                'email' => 'finance.manager@solvesta.com',
                'role' => 'accountant',
                'employee_data' => [
                    'employee_id' => 'EMP011',
                    'first_name' => 'هند',
                    'last_name' => 'علي',
                    'phone' => '+966501234577',
                    'salary' => 24000,
                    'position' => 'Finance Manager',
                    'department_id' => 5, // المالية
                ]
            ],
        ];

        foreach ($managers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($userData['employee_data'], [
                    'email' => $userData['email'],
                    'hire_date' => now()->subMonths(rand(3, 18)),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'user_id' => $user->id,
                ])
            );
        }

        // إنشاء المطورين
        $developers = [
            [
                'name' => 'عبدالله خالد',
                'email' => 'developer1@solvesta.com',
                'role' => 'developer',
                'employee_data' => [
                    'employee_id' => 'EMP012',
                    'first_name' => 'عبدالله',
                    'last_name' => 'خالد',
                    'phone' => '+966501234578',
                    'salary' => 18000,
                    'position' => 'Senior Developer',
                    'department_id' => 4,
                ]
            ],
            [
                'name' => 'سعد محمد',
                'email' => 'developer2@solvesta.com',
                'role' => 'developer',
                'employee_data' => [
                    'employee_id' => 'EMP013',
                    'first_name' => 'سعد',
                    'last_name' => 'محمد',
                    'phone' => '+966501234579',
                    'salary' => 15000,
                    'position' => 'Developer',
                    'department_id' => 4,
                ]
            ],
            [
                'name' => 'رنا أحمد',
                'email' => 'developer3@solvesta.com',
                'role' => 'developer',
                'employee_data' => [
                    'employee_id' => 'EMP014',
                    'first_name' => 'رنا',
                    'last_name' => 'أحمد',
                    'phone' => '+966501234580',
                    'salary' => 16000,
                    'position' => 'Frontend Developer',
                    'department_id' => 4,
                ]
            ],
        ];

        foreach ($developers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($userData['employee_data'], [
                    'email' => $userData['email'],
                    'hire_date' => now()->subMonths(rand(1, 12)),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'user_id' => $user->id,
                ])
            );
        }

        // إنشاء المصممين
        $designers = [
            [
                'name' => 'ليلى حسن',
                'email' => 'designer1@solvesta.com',
                'role' => 'designer',
                'employee_data' => [
                    'employee_id' => 'EMP015',
                    'first_name' => 'ليلى',
                    'last_name' => 'حسن',
                    'phone' => '+966501234581',
                    'salary' => 14000,
                    'position' => 'UI/UX Designer',
                    'department_id' => 5,
                ]
            ],
            [
                'name' => 'طارق يوسف',
                'email' => 'designer2@solvesta.com',
                'role' => 'designer',
                'employee_data' => [
                    'employee_id' => 'EMP016',
                    'first_name' => 'طارق',
                    'last_name' => 'يوسف',
                    'phone' => '+966501234582',
                    'salary' => 13000,
                    'position' => 'Graphic Designer',
                    'department_id' => 5,
                ]
            ],
        ];

        foreach ($designers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($userData['employee_data'], [
                    'email' => $userData['email'],
                    'hire_date' => now()->subMonths(rand(1, 8)),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'user_id' => $user->id,
                ])
            );
        }

        // إنشاء باقي الموظفين
        $otherEmployees = [
            [
                'name' => 'نور الدين',
                'email' => 'qa1@solvesta.com',
                'role' => 'developer',
                'employee_data' => [
                    'employee_id' => 'EMP017',
                    'first_name' => 'نور',
                    'last_name' => 'الدين',
                    'phone' => '+966501234583',
                    'salary' => 12000,
                    'position' => 'QA Tester',
                    'department_id' => 6,
                ]
            ],
            [
                'name' => 'أحمد سعد',
                'email' => 'sales1@solvesta.com',
                'role' => 'employee',
                'employee_data' => [
                    'employee_id' => 'EMP018',
                    'first_name' => 'أحمد',
                    'last_name' => 'سعد',
                    'phone' => '+966501234584',
                    'salary' => 11000,
                    'position' => 'Sales Representative',
                    'department_id' => 7,
                ]
            ],
            [
                'name' => 'فاطمة محمد',
                'email' => 'support1@solvesta.com',
                'role' => 'support',
                'employee_data' => [
                    'employee_id' => 'EMP019',
                    'first_name' => 'فاطمة',
                    'last_name' => 'محمد',
                    'phone' => '+966501234585',
                    'salary' => 10000,
                    'position' => 'Support Agent',
                    'department_id' => 6,
                ]
            ],
            [
                'name' => 'خالد عبدالله',
                'email' => 'hr1@solvesta.com',
                'role' => 'hr',
                'employee_data' => [
                    'employee_id' => 'EMP020',
                    'first_name' => 'خالد',
                    'last_name' => 'عبدالله',
                    'phone' => '+966501234586',
                    'salary' => 11500,
                    'position' => 'HR Specialist',
                    'department_id' => 2,
                ]
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'accountant1@solvesta.com',
                'role' => 'accountant',
                'employee_data' => [
                    'employee_id' => 'EMP021',
                    'first_name' => 'سارة',
                    'last_name' => 'أحمد',
                    'phone' => '+966501234587',
                    'salary' => 12500,
                    'position' => 'Accountant',
                    'department_id' => 5,
                ]
            ],
        ];

        foreach ($otherEmployees as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($userData['role']);

            Employee::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($userData['employee_data'], [
                    'email' => $userData['email'],
                    'hire_date' => now()->subMonths(rand(1, 6)),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'user_id' => $user->id,
                ])
            );
        }
    }
}
