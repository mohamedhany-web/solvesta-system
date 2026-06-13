<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSystemFeatureUpdate extends Model
{
    protected $fillable = [
        'client_system_feature_id',
        'title',
        'body',
        'visibility',
        'created_by_user_id',
        'created_by_client_account_id',
    ];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(ClientSystemFeature::class, 'client_system_feature_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function clientAuthor(): BelongsTo
    {
        return $this->belongsTo(ClientAccount::class, 'created_by_client_account_id');
    }

    public function getVisibilityLabelAttribute(): string
    {
        return $this->visibility === 'client' ? 'يظهر للعميل' : 'داخلي';
    }
}
