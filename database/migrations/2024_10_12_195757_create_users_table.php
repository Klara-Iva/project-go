<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id'); 
            $table->unsignedBigInteger('team_id')->nullable(true);
            $table->integer('annual_leave_days')->default(20);
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
         });
    }
    
//TODO make user-team table for multiple teams for one user
    public function down()
    {
        Schema::dropIfExists('users');
    }
    
}