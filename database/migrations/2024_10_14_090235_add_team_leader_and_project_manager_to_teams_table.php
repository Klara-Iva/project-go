<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamLeaderAndProjectManagerToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {

            $table->foreignId('team_leader_id')
                ->nullable() 
                ->constrained('users')
                ->onDelete('cascade');

      
            $table->foreignId('project_manager_id')
                ->nullable() 
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['team_leader_id']);
            $table->dropForeign(['project_manager_id']);
            $table->dropColumn('team_leader_id');
            $table->dropColumn('project_manager_id');
        });
    }
}
