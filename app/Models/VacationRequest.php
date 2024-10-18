<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'days_requested',
        'team_leader_approved',
        'project_manager_approved',
        'status',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}