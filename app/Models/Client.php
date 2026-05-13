<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'address',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(ClientAccount::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(ClientNotification::class)->orderByDesc('created_at');
    }

    public function sharedDocuments(): HasMany
    {
        return $this->hasMany(ClientSharedDocument::class)->orderByDesc('created_at');
    }
    public function serviceReports(): HasMany
    {
        return $this->hasMany(ClientServiceReport::class)->orderByDesc('created_at');
    }

    public function websiteIssues(): HasMany
    {
        return $this->hasMany(ClientWebsiteIssue::class)->orderByDesc('created_at');
    }

    public function meetingRequests(): HasMany
    {
        return $this->hasMany(ClientMeetingRequest::class)->orderByDesc('created_at');
    }
}
