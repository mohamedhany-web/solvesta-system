<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\SettingsHelper::getCompanyName() }} - بوابة العملاء</title>

    @php $faviconUrl = \App\Helpers\SettingsHelper::getFaviconUrl(); @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl ?: '/favicon.ico' }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl ?: '/favicon.ico' }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;900&family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { tajawal: ['Tajawal','sans-serif'], cairo: ['Cairo','sans-serif'] } } }
        }
    </script>

    @php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
    <style>
        :root { --brand: {{ $themeColor }}; }
        .btn-brand { background: linear-gradient(135deg, var(--brand) 0%, color-mix(in srgb, var(--brand) 85%, #000) 100%); }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4 font-tajawal overflow-x-hidden">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            @php
                $logoUrl = \App\Helpers\SettingsHelper::getLogoUrl();
                $companyName = \App\Helpers\SettingsHelper::getCompanyName();
            @endphp

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6 shadow-lg"
                 style="background: linear-gradient(135deg, var(--brand) 0%, color-mix(in srgb, var(--brand) 85%, #000) 100%);">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
                @else
                    <div class="text-white font-extrabold font-cairo text-3xl">{{ mb_substr($companyName,0,1) }}</div>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold font-cairo text-gray-900 mb-2">بوابة العملاء</h1>
            <p class="text-gray-600">لعملاء {{ $companyName }} فقط</p>
            <div class="w-20 h-1 bg-gray-200 rounded-full mx-auto mt-4"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-7 border border-gray-100">
            <div class="text-center mb-7">
                <h2 class="text-xl font-extrabold text-gray-900 font-cairo">تسجيل الدخول</h2>
                <p class="mt-2 text-sm text-gray-500">أدخل بيانات حسابك للوصول إلى لوحتك</p>
            </div>

            <form method="POST" action="{{ route('client.login.submit') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 bg-red-50 @enderror"
                           placeholder="example@company.com">
                    @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">كلمة المرور</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-500 bg-red-50 @enderror"
                           placeholder="••••••••">
                    @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        تذكرني
                    </label>
                    <a href="{{ route('website.contact') }}" class="text-sm font-bold" style="color:var(--brand)">
                        تواصل معنا
                    </a>
                </div>

                <button type="submit" class="w-full text-white font-extrabold py-3.5 px-6 rounded-xl btn-brand shadow-lg hover:shadow-xl transition">
                    دخول بوابة العملاء
                </button>
            </form>
        </div>

        <div class="text-center mt-7 text-xs text-gray-500">
            <a href="{{ route('website.home') }}" class="hover:text-gray-700">العودة إلى موقع الشركة</a>
        </div>
    </div>
</body>
</html>

