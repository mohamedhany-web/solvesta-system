<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailMessage extends Model
{
    protected $fillable = [
        'imap_uid',
        'message_id',
        'folder',
        'direction',
        'from_email',
        'from_name',
        'to_email',
        'cc',
        'subject',
        'body_html',
        'body_text',
        'is_read',
        'is_starred',
        'forwarded',
        'forwarded_at',
        'in_reply_to_id',
        'created_by',
        'received_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'forwarded' => 'boolean',
        'forwarded_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(self::class, 'in_reply_to_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'in_reply_to_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function preview(int $length = 120): string
    {
        $text = trim(strip_tags((string) ($this->body_text ?: $this->body_html)));
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        return mb_strlen($text) > $length ? mb_substr($text, 0, $length).'…' : $text;
    }

    public function displaySubject(): string
    {
        return $this->subject ?: '(بدون موضوع)';
    }
}
