<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use app\Models\Project;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'annual_leave_days',
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_team', 'user_id', 'team_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class, 'user_id');
    }

}