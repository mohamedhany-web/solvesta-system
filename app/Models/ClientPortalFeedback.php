<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ClientPortalFeedback extends Model
{
    protected $table = 'client_portal_feedbacks';

    protected $fillable = [
        'client_id',
        'feedbackable_type',
        'feedbackable_id',
        'rating',
        'comment',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function feedbackable(): MorphTo
    {
        return $this->morphTo();
    }
}
