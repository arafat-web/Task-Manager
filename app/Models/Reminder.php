<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'time',
        'priority',
        'category',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'is_completed',
        'completed_at',
        'snooze_until',
        'notification_sent',
        'location',
        'tags',
    ];

    protected $casts = [
        'date' => 'datetime',
        'completed_at' => 'datetime',
        'snooze_until' => 'datetime',
        'is_recurring' => 'boolean',
        'is_completed' => 'boolean',
        'notification_sent' => 'boolean',
        'tags' => 'array',
    ];

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    const RECURRENCE_NONE = 'none';
    const RECURRENCE_DAILY = 'daily';
    const RECURRENCE_WEEKLY = 'weekly';
    const RECURRENCE_MONTHLY = 'monthly';
    const RECURRENCE_YEARLY = 'yearly';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('M j, Y') : null;
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? Carbon::parse($this->time)->format('g:i A') : null;
    }

    public function getFormattedDateTimeAttribute()
    {
        if (!$this->date) return null;

        $dateTime = $this->date;
        if ($this->time) {
            $time = Carbon::parse($this->time);
            $dateTime = $dateTime->setTime($time->hour, $time->minute);
        }

        return $dateTime;
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => '#10b981',
            self::PRIORITY_MEDIUM => '#f59e0b',
            self::PRIORITY_HIGH => '#ef4444',
            self::PRIORITY_URGENT => '#dc2626',
            default => '#64748b'
        };
    }

    public function getPriorityLabelAttribute()
    {
        return ucfirst($this->priority ?? 'medium');
    }

    public function getIsOverdueAttribute()
    {
        if ($this->is_completed || !$this->formatted_date_time) {
            return false;
        }

        return $this->formatted_date_time->isPast();
    }

    public function getIsDueSoonAttribute()
    {
        if ($this->is_completed || !$this->formatted_date_time) {
            return false;
        }

        return $this->formatted_date_time->isBetween(now(), now()->addHours(24));
    }

    public function getTimeUntilAttribute()
    {
        if (!$this->formatted_date_time) return null;

        return $this->formatted_date_time->diffForHumans();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeOverdue($query)
    {
        return $query->active()
            ->whereNotNull('date')
            ->whereRaw('CONCAT(date, " ", COALESCE(time, "00:00:00")) < NOW()');
    }

    public function scopeDueToday($query)
    {
        return $query->active()
            ->whereDate('date', today());
    }

    public function scopeDueSoon($query)
    {
        return $query->active()
            ->whereBetween('date', [now(), now()->addHours(24)]);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now()
        ]);

        // Create next occurrence if recurring
        if ($this->is_recurring && $this->recurrence_type !== self::RECURRENCE_NONE) {
            $this->createNextOccurrence();
        }
    }

    public function snooze($minutes = 15)
    {
        $this->update([
            'snooze_until' => now()->addMinutes($minutes),
            'notification_sent' => false
        ]);
    }

    protected function createNextOccurrence()
    {
        if (!$this->date) return;

        $nextDate = $this->calculateNextDate();
        if ($nextDate) {
            $this->replicate([
                'is_completed',
                'completed_at',
                'notification_sent',
                'snooze_until'
            ])->fill([
                'date' => $nextDate,
                'is_completed' => false,
                'completed_at' => null,
                'notification_sent' => false,
                'snooze_until' => null,
            ])->save();
        }
    }

    protected function calculateNextDate()
    {
        $currentDate = $this->date;
        $interval = $this->recurrence_interval ?: 1;

        return match($this->recurrence_type) {
            self::RECURRENCE_DAILY => $currentDate->addDays($interval),
            self::RECURRENCE_WEEKLY => $currentDate->addWeeks($interval),
            self::RECURRENCE_MONTHLY => $currentDate->addMonths($interval),
            self::RECURRENCE_YEARLY => $currentDate->addYears($interval),
            default => null
        };
    }
}
