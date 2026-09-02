<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Department;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'start_time',
        'end_time',
        'location',
        'meeting_link',
        'organizer_id',
        'department_id',
        'send_email_reminders',
        'reminder_1d_sent_at',
        'reminder_1h_sent_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'send_email_reminders' => 'boolean',
        'reminder_1d_sent_at' => 'datetime',
        'reminder_1h_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Unique recipient emails: organizer + participants.
     *
     * @return array<int, string>
     */
    public function reminderRecipientEmails(): array
    {
        $this->loadMissing(['organizer', 'participants.user']);

        $emails = [];
        if ($this->organizer?->email) {
            $emails[] = strtolower(trim($this->organizer->email));
        }
        foreach ($this->participants as $participant) {
            $email = $participant->user?->email;
            if ($email) {
                $emails[] = strtolower(trim($email));
            }
        }

        return array_values(array_unique(array_filter($emails, fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))));
    }
}









