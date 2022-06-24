<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public const PENDING = "PENDING";
    public const APPROVED = "APPROVED";
    public const DENIED = "DENIED";
    public const CANCELLED = "CANCELLED";


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'location',
        'limit',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function participant()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function hasParticipant($userId)
    {
        return $this->participant()
            ->where('user_id', $userId)
            ->exists();
    }
}
