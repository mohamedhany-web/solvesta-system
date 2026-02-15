<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المشاريع</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', 'Arial', sans-serif; line-height: 1.6; color: #2d3748; background: #ffffff; }
        .report-container { max-width: 297mm; margin: 0 auto; padding: 20px; }
        
        /* Header */
        .report-header { border-bottom: 2px solid #1a202c; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: 700; color: #1a202c; margin-bottom: 5px; }
        .report-title { font-size: 18px; font-weight: 600; color: #4a5568; margin-bottom: 10px; }
        .report-date { font-size: 12px; color: #718096; }
        
        /* Summary Box */
        .summary-box { border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 30px; background: #f7fafc; }
        .summary-title { font-size: 14px; font-weight: 600; color: #1a202c; margin-bottom: 15px; border-bottom: 1px solid #cbd5e0; padding-bottom: 8px; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .summary-item { text-align: center; }
        .summary-label { font-size: 11px; color: #718096; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: 600; color: #1a202c; }
        
        /* Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10px; }
        .data-table thead th { background: #2d3748; color: #ffffff; padding: 8px 6px; text-align: right; font-weight: 600; font-size: 10px; border: 1px solid #1a202c; }
        .data-table tbody td { padding: 6px; border: 1px solid #e2e8f0; text-align: right; background: #ffffff; }
        .data-table tbody tr:nth-child(even) td { background: #f7fafc; }
        .data-table tfoot td { padding: 8px 6px; border: 1px solid #2d3748; font-weight: 600; background: #edf2f7; color: #1a202c; }
        
        .project-name { font-weight: 600; color: #1a202c; }
        .budget-amount { font-weight: 600; text-align: left; }
        .progress-bar-container { width: 100%; height: 16px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: #4a5568; text-align: center; color: white; font-size: 9px; line-height: 16px; }
        
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 9px; font-weight: 500; }
        .status-planning { background: #e2e8f0; color: #4a5568; }
        .status-in_progress { background: #bee3f8; color: #2c5282; }
        .status-on_hold { background: #fed7d7; color: #742a2a; }
        .status-completed { background: #c6f6d5; color: #22543d; }
        .status-cancelled { background: #fed7d7; color: #742a2a; }
        
        /* Footer */
        .report-footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .signature-section { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .signature-box { text-align: center; }
        .signature-line { border-top: 1px solid #2d3748; margin-bottom: 8px; padding-top: 40px; }
        .signature-label { font-size: 11px; color: #4a5568; }
        
        @media print {
            body { background: white; }
            .report-container { padding: 0; }
            .avoid-break { page-break-inside: avoid; }
        }
        
        .no-data { text-align: center; padding: 40px; color: #718096; font-size: 14px; }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <div class="report-header">
            <div class="company-name">{{ \App\Helpers\SettingsHelper::getCompanyName() }}</div>
            <div class="report-title">تقرير المشاريع</div>
            <div class="report-date">تاريخ التقرير: {{ now()->format('Y-m-d') }} | {{ now()->format('h:i A') }}</div>
        </div>
        
        @if($projects->count() > 0)
            <!-- Summary -->
            <div class="summary-box avoid-break">
                <div class="summary-title">ملخص التقرير</div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">إجمالي عدد المشاريع</div>
                        <div class="summary-value">{{ $summary['total'] }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">إجمالي الميزانية</div>
                        <div class="summary-value">{{ number_format($summary['total_budget'], 0) }} ج.م</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">متوسط نسبة الإنجاز</div>
                        <div class="summary-value">{{ round($summary['average_progress'], 1) }}%</div>
                    </div>
                </div>
            </div>
            
            <!-- Projects Table -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">م</th>
                        <th style="width: 20%;">اسم المشروع</th>
                        <th style="width: 15%;">العميل</th>
                        <th style="width: 12%;">القسم</th>
                        <th style="width: 10%;">الميزانية</th>
                        <th style="width: 12%;">تاريخ البدء</th>
                        <th style="width: 12%;">تاريخ الانتهاء</th>
                        <th style="width: 14%;">نسبة الإنجاز</th>
                        <th style="width: 10%;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $index => $project)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="project-name">{{ $project->name }}</td>
                        <td>{{ $project->client->name ?? '-' }}</td>
                        <td>{{ $project->department->name ?? '-' }}</td>
                        <td class="budget-amount">{{ number_format($project->budget, 0) }} ج.م</td>
                        <td>{{ $project->start_date ? $project->start_date->format('Y-m-d') : '-' }}</td>
                        <td>{{ $project->end_date ? $project->end_date->format('Y-m-d') : '-' }}</td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $project->progress_percentage }}%;">
                                    {{ $project->progress_percentage }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $project->status }}">
                                @if($project->status === 'planning') التخطيط
                                @elseif($project->status === 'in_progress') قيد التنفيذ
                                @elseif($project->status === 'on_hold') معلق
                                @elseif($project->status === 'completed') مكتمل
                                @elseif($project->status === 'cancelled') ملغي
                                @else {{ $project->status }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: left;">الإجمالي</td>
                        <td class="budget-amount">{{ number_format($summary['total_budget'], 0) }} ج.م</td>
                        <td colspan="4">{{ $summary['total'] }} مشروع</td>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Footer with Signatures -->
            <div class="report-footer avoid-break">
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">إعداد</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">مراجعة</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">اعتماد</div>
                    </div>
                </div>
            </div>
        @else
            <div class="no-data">
                <p>لا توجد مشاريع مطابقة للمعايير المحددة</p>
            </div>
        @endif
    </div>
    
    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>


