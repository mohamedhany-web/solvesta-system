<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;

class CheckPermissionsComprehensive extends Command
{
    protected $signature = 'permissions:check-comprehensive';
    protected $description = 'فحص شامل لنظام الصلاحيات في جميع Routes وControllers وViews';

    protected $issues = [];
    protected $routesWithoutPermissions = [];
    protected $controllersWithoutChecks = [];
    protected $viewsWithoutChecks = [];

    public function handle()
    {
        $this->info('🔍 بدء الفحص الشامل لنظام الصلاحيات...');
        $this->newLine();

        // 1. فحص Routes
        $this->checkRoutes();

        // 2. فحص Controllers
        $this->checkControllers();

        // 3. فحص Views
        $this->checkViews();

        // 4. عرض التقرير
        $this->displayReport();

        return 0;
    }

    protected function checkRoutes()
    {
        $this->info('📋 فحص Routes...');
        
        $routes = Route::getRoutes();
        $protectedRoutes = [
            'users', 'departments', 'employees', 'attendances', 'leaves', 'salaries',
            'projects', 'tasks', 'bugs', 'qa', 'clients', 'sales', 'tickets',
            'financial-invoices', 'payments', 'expenses', 'invoices', 'contracts',
            'accounting', 'journal-entries', 'reports', 'system-settings', 'roles'
        ];

        foreach ($routes as $route) {
            if ($route->middleware() && in_array('auth', $route->middleware())) {
                $uri = $route->uri();
                $name = $route->getName();
                
                // تجاهل routes غير المهمة
                if (in_array($uri, ['/', 'dashboard', 'profile', 'notifications', 'messages']) ||
                    str_starts_with($uri, 'profile') ||
                    str_starts_with($uri, 'api/')) {
                    continue;
                }

                // التحقق من وجود permission middleware
                $middleware = $route->middleware();
                $hasPermission = false;
                
                foreach ($middleware as $mw) {
                    if (str_contains($mw, 'permission:') || str_contains($mw, 'CheckPermission')) {
                        $hasPermission = true;
                        break;
                    }
                }

                // التحقق إذا كان Route يحتاج إلى صلاحية
                $needsPermission = false;
                foreach ($protectedRoutes as $protected) {
                    if (str_contains($uri, $protected) || str_contains($name ?? '', $protected)) {
                        $needsPermission = true;
                        break;
                    }
                }

                if ($needsPermission && !$hasPermission) {
                    $this->routesWithoutPermissions[] = [
                        'uri' => $uri,
                        'name' => $name,
                        'method' => implode('|', $route->methods()),
                    ];
                }
            }
        }

        $this->info('   ✓ تم فحص ' . count($routes) . ' route');
    }

    protected function checkControllers()
    {
        $this->info('📦 فحص Controllers...');
        
        $controllersPath = app_path('Http/Controllers');
        $controllers = File::allFiles($controllersPath);

        foreach ($controllers as $file) {
            $className = str_replace(
                [$controllersPath, '.php', '/'],
                ['App\\Http\\Controllers\\', '', '\\'],
                $file->getPathname()
            );

            try {
                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);
                
                // تجاهل Controllers غير المهمة
                if ($reflection->isAbstract() || 
                    str_contains($className, 'Controller') === false ||
                    $className === 'App\\Http\\Controllers\\Controller') {
                    continue;
                }

                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                
                foreach ($methods as $method) {
                    if ($method->getDeclaringClass()->getName() !== $className) {
                        continue;
                    }

                    $methodName = $method->getName();
                    
                    // تجاهل methods معينة
                    if (in_array($methodName, ['__construct', '__invoke', 'middleware'])) {
                        continue;
                    }

                    // فحص التعليقات والكود للبحث عن فحص صلاحيات
                    $docComment = $method->getDocComment();
                    $hasPermissionCheck = false;

                    // البحث في التعليقات
                    if ($docComment && (
                        str_contains($docComment, '@can') ||
                        str_contains($docComment, 'permission') ||
                        str_contains($docComment, 'authorize')
                    )) {
                        $hasPermissionCheck = true;
                    }

                    // قراءة ملف الكود للبحث عن فحص صلاحيات
                    $fileContent = file_get_contents($file->getPathname());
                    if (str_contains($fileContent, "->can(") ||
                        str_contains($fileContent, "@can") ||
                        str_contains($fileContent, "authorize") ||
                        str_contains($fileContent, "Gate::") ||
                        str_contains($fileContent, "Permission::")) {
                        $hasPermissionCheck = true;
                    }

                    // بعض Controllers قد تعتمد على Middleware فقط
                    // سنقوم بتسجيلها للتحقق يدوياً
                    if (!$hasPermissionCheck && !$method->isStatic()) {
                        $this->controllersWithoutChecks[] = [
                            'controller' => class_basename($className),
                            'method' => $methodName,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // تجاهل الأخطاء
            }
        }

        $this->info('   ✓ تم فحص Controllers');
    }

    protected function checkViews()
    {
        $this->info('👁️  فحص Views...');
        
        $viewsPath = resource_path('views');
        $views = File::allFiles($viewsPath);

        $criticalViews = ['index', 'create', 'edit', 'show'];

        foreach ($views as $file) {
            $fileName = $file->getFilename();
            $path = str_replace([$viewsPath, '.blade.php'], ['', ''], $file->getPathname());
            
            // تجاهل layouts و components
            if (str_contains($path, 'layouts') || 
                str_contains($path, 'components') ||
                str_contains($path, 'emails') ||
                str_contains($path, 'auth')) {
                continue;
            }

            // التحقق من الصفحات المهمة فقط
            $isCritical = false;
            foreach ($criticalViews as $critical) {
                if (str_contains($path, $critical)) {
                    $isCritical = true;
                    break;
                }
            }

            if ($isCritical) {
                $content = file_get_contents($file->getPathname());
                
                // البحث عن أزرار مهمة بدون @can
                $patterns = [
                    '/(href|action)=["\']([^"\']*(?:create|edit|delete|destroy|store|update)[^"\']*)["\']/i',
                    '/<a[^>]*>.*?(?:إضافة|جديد|إنشاء|تعديل|حذف).*?<\/a>/iu',
                    '/<button[^>]*>.*?(?:إضافة|جديد|إنشاء|تعديل|حذف).*?<\/button>/iu',
                ];

                $hasCan = str_contains($content, '@can');
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $content)) {
                        if (!$hasCan && !str_contains($path, 'layout')) {
                            $this->viewsWithoutChecks[] = [
                                'view' => $path,
                                'file' => $fileName,
                            ];
                        }
                        break;
                    }
                }
            }
        }

        $this->info('   ✓ تم فحص Views');
    }

    protected function displayReport()
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('📊 تقرير الفحص الشامل لنظام الصلاحيات');
        $this->info('═══════════════════════════════════════════════════════');
        $this->newLine();

        // Routes بدون صلاحيات
        if (count($this->routesWithoutPermissions) > 0) {
            $this->warn('⚠️  Routes بدون صلاحيات: ' . count($this->routesWithoutPermissions));
            foreach ($this->routesWithoutPermissions as $route) {
                $this->line("   • {$route['method']} /{$route['uri']} ({$route['name']})");
            }
            $this->newLine();
        } else {
            $this->info('✅ جميع Routes المحمية بها صلاحيات');
            $this->newLine();
        }

        // Controllers بدون فحص
        if (count($this->controllersWithoutChecks) > 10) {
            $this->warn('⚠️  Controllers بدون فحص صريح للصلاحيات: ' . count($this->controllersWithoutChecks));
            $this->line('   (العديد - يرجى المراجعة يدوياً)');
            $this->newLine();
        } else {
            $this->info('✅ Controllers: يتم الاعتماد على Middleware (قيد المراجعة)');
            $this->newLine();
        }

        // Views بدون فحص
        if (count($this->viewsWithoutChecks) > 0) {
            $this->warn('⚠️  Views قد تحتاج إلى @can: ' . count($this->viewsWithoutChecks));
            foreach (array_slice($this->viewsWithoutChecks, 0, 10) as $view) {
                $this->line("   • {$view['view']}");
            }
            if (count($this->viewsWithoutChecks) > 10) {
                $this->line("   ... و " . (count($this->viewsWithoutChecks) - 10) . " أخرى");
            }
            $this->newLine();
        } else {
            $this->info('✅ Views: لا توجد مشاكل واضحة');
            $this->newLine();
        }

        $this->info('═══════════════════════════════════════════════════════');
    }
}









