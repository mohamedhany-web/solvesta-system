<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مهمة جديدة مخصصة لك</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .task-details {
            background-color: #f8f9fa;
            border-right: 4px solid #667eea;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #212529;
        }
        .priority {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-low { background-color: #d4edda; color: #155724; }
        .priority-medium { background-color: #fff3cd; color: #856404; }
        .priority-high { background-color: #ffeaa7; color: #d63031; }
        .priority-urgent { background-color: #fdcb6e; color: #e17055; }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>مهمة جديدة مخصصة لك</h1>
        </div>

        <div class="content">
            <p>مرحباً <strong>{{ $assignedUser->name }}</strong>,</p>

            <p>تم تعيين مهمة جديدة لك في النظام. يرجى مراجعة التفاصيل أدناه:</p>

            <div class="task-details">
                <div class="detail-row">
                    <span class="detail-label">عنوان المهمة:</span>
                    <span class="detail-value">{{ $task->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الوصف:</span>
                    <span class="detail-value">{{ Str::limit($task->description, 100) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">المشروع:</span>
                    <span class="detail-value">{{ $projectName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الأولوية:</span>
                    <span class="detail-value">
                        <span class="priority priority-{{ $task->priority }}">{{ $priorityName }}</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">تاريخ الاستحقاق:</span>
                    <span class="detail-value">{{ $dueDate }}</span>
                </div>
                @if($task->estimated_hours)
                <div class="detail-row">
                    <span class="detail-label">الساعات المتوقعة:</span>
                    <span class="detail-value">{{ $task->estimated_hours }} ساعة</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">تم الإنشاء بواسطة:</span>
                    <span class="detail-value">{{ $createdBy->name }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $taskUrl }}" class="button">عرض المهمة في النظام</a>
            </div>

            <p>يرجى مراجعة المهمة والبدء في العمل عليها في أقرب وقت ممكن.</p>
        </div>

        <div class="footer">
            <p>هذا بريد إلكتروني تلقائي من نظام إدارة المهام - Solvesta</p>
            <p>إذا كان لديك أي استفسارات، يرجى التواصل مع مدير المشروع</p>
        </div>
    </div>
</body>
</html>

