<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ATS Pipeline Stages (software hiring funnel)
    |--------------------------------------------------------------------------
    */
    'stages' => [
        'applied' => 'تقديم',
        'screening' => 'فرز أولي',
        'technical' => 'تقييم تقني',
        'interview' => 'مقابلة',
        'offer' => 'عرض وظيفي',
        'hired' => 'تم التوظيف',
        'rejected' => 'مرفوض',
    ],

    'status_to_stage' => [
        'pending' => 'applied',
        'reviewing' => 'screening',
        'shortlisted' => 'technical',
        'hired' => 'hired',
        'rejected' => 'rejected',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default evaluation criteria for software roles
    |--------------------------------------------------------------------------
    | type: auto = scored from CV parse / form data
    |       manual = filled by HR / interviewer
    */
    'default_criteria' => [
        [
            'code' => 'skills_match',
            'label' => 'مطابقة المهارات التقنية',
            'description' => 'نسبة تطابق مهارات الـ CV مع متطلبات الوظيفة',
            'type' => 'auto',
            'weight' => 30,
            'sort_order' => 10,
            'config' => ['source' => 'skills'],
        ],
        [
            'code' => 'experience_years',
            'label' => 'سنوات الخبرة',
            'description' => 'تقدير سنوات الخبرة من السيرة الذاتية',
            'type' => 'auto',
            'weight' => 20,
            'sort_order' => 20,
            'config' => ['source' => 'years', 'target_years' => 3],
        ],
        [
            'code' => 'requirements_keywords',
            'label' => 'كلمات المتطلبات',
            'description' => 'تطابق كلمات مفتاحية من نص المتطلبات',
            'type' => 'auto',
            'weight' => 20,
            'sort_order' => 30,
            'config' => ['source' => 'requirements'],
        ],
        [
            'code' => 'portfolio_presence',
            'label' => 'Portfolio / LinkedIn / GitHub',
            'description' => 'وجود روابط مهنية داعمة',
            'type' => 'auto',
            'weight' => 10,
            'sort_order' => 40,
            'config' => ['source' => 'links'],
        ],
        [
            'code' => 'communication',
            'label' => 'جودة الرسالة التعريفية',
            'description' => 'تقييم أولي لرسالة المتقدم',
            'type' => 'auto',
            'weight' => 10,
            'sort_order' => 50,
            'config' => ['source' => 'cover_letter'],
        ],
        [
            'code' => 'technical_interview',
            'label' => 'التقييم التقني (يدوي)',
            'description' => 'درجة المقابلة التقنية / الاختبار',
            'type' => 'manual',
            'weight' => 25,
            'sort_order' => 60,
            'config' => [],
        ],
        [
            'code' => 'culture_fit',
            'label' => 'الملاءمة الثقافية (يدوي)',
            'description' => 'تقييم الملاءمة مع فريق وثقافة الشركة',
            'type' => 'manual',
            'weight' => 15,
            'sort_order' => 70,
            'config' => [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Skill dictionary used for CV extraction (software companies)
    |--------------------------------------------------------------------------
    */
    'skills_dictionary' => [
        'php', 'laravel', 'javascript', 'typescript', 'react', 'vue', 'angular', 'node', 'nodejs',
        'python', 'django', 'fastapi', 'java', 'spring', 'kotlin', 'swift', 'flutter', 'dart',
        'go', 'golang', 'rust', 'c#', 'csharp', '.net', 'dotnet', 'sql', 'mysql', 'postgresql',
        'mongodb', 'redis', 'elasticsearch', 'docker', 'kubernetes', 'aws', 'azure', 'gcp',
        'cloudflare', 'nginx', 'linux', 'git', 'ci/cd', 'github actions', 'gitlab',
        'rest', 'graphql', 'microservices', 'tailwind', 'bootstrap', 'html', 'css', 'sass',
        'figma', 'ui/ux', 'qa', 'selenium', 'cypress', 'phpunit', 'pest', 'jest',
        'machine learning', 'ai', 'nlp', 'data analysis', 'power bi', 'excel',
        'agile', 'scrum', 'jira', 'devops', 'terraform', 'ansible',
    ],

    'education_keywords' => [
        'phd' => 'دكتوراه',
        'doctorate' => 'دكتوراه',
        'master' => 'ماجستير',
        'msc' => 'ماجستير',
        'mba' => 'ماجستير',
        'bachelor' => 'بكالوريوس',
        'bsc' => 'بكالوريوس',
        'بكالوريوس' => 'بكالوريوس',
        'ماجستير' => 'ماجستير',
        'دبلوم' => 'دبلوم',
        'diploma' => 'دبلوم',
    ],

    /*
    |--------------------------------------------------------------------------
    | OCR for scanned / CamScanner CVs
    |--------------------------------------------------------------------------
    */
    'ocr' => [
        'enabled' => env('ATS_OCR_ENABLED', true),
        'max_pages' => (int) env('ATS_OCR_MAX_PAGES', 4),
        'psm' => (int) env('ATS_OCR_PSM', 6),
        'tesseract_path' => env('TESSERACT_PATH', ''),
        'tessdata_dir' => env('TESSDATA_DIR', storage_path('app/tessdata')),
        'tesseract_lang' => env('TESSERACT_LANG', 'eng+ara'),
        // Fallback cloud OCR when Tesseract is unavailable or weak
        'api_enabled' => env('OCR_SPACE_ENABLED', true),
        'api_key' => env('OCR_SPACE_API_KEY', ''),
        'api_language' => env('OCR_SPACE_LANG', 'eng'),
        'api_timeout' => (int) env('OCR_SPACE_TIMEOUT', 90),
    ],
];
