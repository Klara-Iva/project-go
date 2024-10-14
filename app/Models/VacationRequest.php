<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'days_requested',
        'team_leader_approved',
        'project_manager_approved',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}