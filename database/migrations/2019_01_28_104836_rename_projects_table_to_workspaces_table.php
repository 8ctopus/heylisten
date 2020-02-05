<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameProjectsTableToWorkspacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('projects', 'workspaces');

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('project_id', 'workspace_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('workspaces', 'projects');

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('workspace_id', 'project_id');
        });
    }
}
