<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSharedDocument extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'document_type',
        'file_path',
        'original_filename',
        'mime',
        'notes',
        'uploaded_by',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
