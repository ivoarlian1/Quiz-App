<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'token',
        'is_active',
        'time_limit'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($quiz) {
            $quiz->token = static::generateUniqueToken();
        });

        static::saved(function ($quiz) {
            Cache::forget('quiz_' . $quiz->id . '_total_points');
        });
    }

    protected static function generateUniqueToken()
    {
        do {
            $token = Str::random(8);
        } while (static::where('token', $token)->exists());
        
        return $token;
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getTotalPointsAttribute()
    {
        return Cache::remember('quiz_' . $this->id . '_total_points', 3600, function () {
            return $this->questions()->sum('points');
        });
    }
} 