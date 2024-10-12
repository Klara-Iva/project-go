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
        'status',
        'team_leader_approved', //TODO fix the html according to this 
        'project_manager_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}