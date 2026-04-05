<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate unique slug on creating/updating.
     */
    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            $project->slug = static::uniqueSlug($project->name, $project->id);
        });

        static::updating(function (Project $project) {
            if ($project->isDirty('name')) {
                $project->slug = static::uniqueSlug($project->name, $project->id);
            }
        });
    }

    private static function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function getDerivedStatusAttribute()
    {
        $today = Carbon::now();

        if ($this->start_date && $today->lt($this->start_date)) {
            return 'pending';
        }

        if ($this->end_date && $this->end_date->lt($today)) {
            $unfinishedTasks = $this->tasks()->where('status', '!=', 'completed')->count();
            return $unfinishedTasks > 0 ? 'unfinished' : 'finished';
        }

        return 'on_going';
    }

    public function teamProjects()
    {
        return $this->belongsToMany(ProjectTeam::class, 'project_teams', 'project_id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id');
    }
}
