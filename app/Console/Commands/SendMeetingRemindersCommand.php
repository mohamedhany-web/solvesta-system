<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMeetingRemindersCommand extends Command
{
    protected $signature = 'meetings:send-reminders';

    protected $description = 'Send email reminders for upcoming meetings (24h and 1h before start)';

    public function handle(EmailService $mailer): int
    {
        $now = now();
        $sent1d = 0;
        $sent1h = 0;

        // 24-hour window: meeting starts within the next 24 hours
        $dayMeetings = Meeting::query()
            ->with(['organizer', 'participants.user'])
            ->where('send_email_reminders', true)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereNull('reminder_1d_sent_at')
            ->where('start_time', '>', $now)
            ->where('start_time', '<=', $now->copy()->addDay())
            ->get();

        foreach ($dayMeetings as $meeting) {
            try {
                $count = $mailer->sendMeetingRemindersToRecipients($meeting, 'تذكير باجتماع خلال 24 ساعة');
                $meeting->update(['reminder_1d_sent_at' => now()]);
                $sent1d++;
                Log::info('Meeting 24h reminder sent', [
                    'meeting_id' => $meeting->id,
                    'emails_sent' => $count,
                ]);
            } catch (\Throwable $e) {
                Log::error('Meeting 24h reminder failed', [
                    'meeting_id' => $meeting->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 1-hour window
        $hourMeetings = Meeting::query()
            ->with(['organizer', 'participants.user'])
            ->where('send_email_reminders', true)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->whereNull('reminder_1h_sent_at')
            ->where('start_time', '>', $now)
            ->where('start_time', '<=', $now->copy()->addHour())
            ->get();

        foreach ($hourMeetings as $meeting) {
            try {
                $count = $mailer->sendMeetingRemindersToRecipients($meeting, 'تذكير باجتماع خلال ساعة');
                $meeting->update(['reminder_1h_sent_at' => now()]);
                $sent1h++;
                Log::info('Meeting 1h reminder sent', [
                    'meeting_id' => $meeting->id,
                    'emails_sent' => $count,
                ]);
            } catch (\Throwable $e) {
                Log::error('Meeting 1h reminder failed', [
                    'meeting_id' => $meeting->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Reminders processed: 24h={$sent1d}, 1h={$sent1h}");

        return self::SUCCESS;
    }
}
