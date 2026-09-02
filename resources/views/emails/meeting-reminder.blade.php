<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكير باجتماع — {{ $meeting->title }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fa;font-family:Tahoma,Arial,sans-serif;color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f4f7fa;padding:24px 12px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#0f766e;color:#ffffff;padding:20px 24px;">
                            <div style="font-size:13px;opacity:.9;">Solvesta</div>
                            <div style="font-size:22px;font-weight:700;margin-top:4px;">{{ $whenLabel }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px;font-size:15px;">مرحباً{{ !empty($recipientName) ? ' '.$recipientName : '' }}،</p>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.7;">
                                تذكير بموعد الاجتماع التالي:
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <div style="font-size:18px;font-weight:700;margin-bottom:10px;">{{ $meeting->title }}</div>
                                        <div style="font-size:14px;margin-bottom:6px;"><strong>الوقت:</strong> {{ $meeting->start_time?->format('Y-m-d H:i') }} — {{ $meeting->end_time?->format('H:i') }}</div>
                                        @if($meeting->location)
                                            <div style="font-size:14px;margin-bottom:6px;"><strong>الموقع:</strong> {{ $meeting->location }}</div>
                                        @endif
                                        @if($meeting->meeting_link)
                                            <div style="font-size:14px;margin-bottom:6px;">
                                                <strong>رابط الاجتماع:</strong>
                                                <a href="{{ $meeting->meeting_link }}" style="color:#0f766e;">{{ $meeting->meeting_link }}</a>
                                            </div>
                                        @endif
                                        @if($meeting->organizer)
                                            <div style="font-size:14px;margin-bottom:6px;"><strong>المنظم:</strong> {{ $meeting->organizer->name }}</div>
                                        @endif
                                        @if($meeting->description)
                                            <div style="font-size:14px;margin-top:10px;color:#475569;line-height:1.6;">{{ \Illuminate\Support\Str::limit(strip_tags($meeting->description), 280) }}</div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0;font-size:13px;color:#64748b;">
                                تم إرسال هذا التذكير تلقائياً من نظام Solvesta.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
