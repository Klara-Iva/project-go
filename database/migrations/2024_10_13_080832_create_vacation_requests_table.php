<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_requested');
            $table->enum('team_leader_approved', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('project_manager_approved', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('team_leader_comment')->nullable();
            $table->text('project_manager_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vacation_requests');
    }

}