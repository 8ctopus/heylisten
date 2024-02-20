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
            //$table->dropForeign('products_project_id_foreign');
            $table->renameColumn('project_id', 'workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces');
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
            $table->dropForeign('products_workspace_id_foreign');
            $table->renameColumn('workspace_id', 'project_id');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }
}
