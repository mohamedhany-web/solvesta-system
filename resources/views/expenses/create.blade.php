@extends('layouts.app')

@section('page-title', 'مصروف جديد')

@section('content')
<div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">مصروف جديد</h1>
            <p class="text-green-100">قم بملء البيانات المطلوبة لإضافة مصروف جديد</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات المصروف</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" id="description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="expense_category" class="block text-sm font-medium text-gray-700 mb-2">الفئة <span class="text-red-500">*</span></label>
                        <select name="expense_category" id="expense_category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('expense_category') border-red-500 @enderror">
                            <option value="">اختر فئة المصروف</option>
                            <option value="office_supplies" {{ old('expense_category') == 'office_supplies' ? 'selected' : '' }}>مستلزمات مكتبية</option>
                            <option value="utilities" {{ old('expense_category') == 'utilities' ? 'selected' : '' }}>مرافق (كهرباء، ماء، إنترنت)</option>
                            <option value="rent" {{ old('expense_category') == 'rent' ? 'selected' : '' }}>إيجار</option>
                            <option value="salaries" {{ old('expense_category') == 'salaries' ? 'selected' : '' }}>رواتب</option>
                            <option value="marketing" {{ old('expense_category') == 'marketing' ? 'selected' : '' }}>تسويق</option>
                            <option value="travel" {{ old('expense_category') == 'travel' ? 'selected' : '' }}>سفر</option>
                            <option value="maintenance" {{ old('expense_category') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                            <option value="software" {{ old('expense_category') == 'software' ? 'selected' : '' }}>برمجيات</option>
                            <option value="professional_fees" {{ old('expense_category') == 'professional_fees' ? 'selected' : '' }}>رسوم مهنية</option>
                            <option value="insurance" {{ old('expense_category') == 'insurance' ? 'selected' : '' }}>تأمين</option>
                            <option value="taxes" {{ old('expense_category') == 'taxes' ? 'selected' : '' }}>ضرائب</option>
                            <option value="other" {{ old('expense_category') == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('expense_category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">المورد (اختياري)</label>
                        <select name="vendor_id" id="vendor_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">اختر المورد</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">المبلغ</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('amount') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ المصروف</label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('expense_date') border-red-500 @enderror">
                        @error('expense_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">طريقة الدفع</label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('payment_method') border-red-500 @enderror">
                            <option value="cash">نقدي</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="check">شيك</option>
                            <option value="credit_card">بطاقة ائتمان</option>
                        </select>
                        @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                    إضافة المصروف
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

