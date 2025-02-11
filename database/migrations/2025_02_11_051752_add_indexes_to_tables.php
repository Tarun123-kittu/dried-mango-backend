<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('email', 'idx_users_email'); 
            $table->index('role_id', 'idx_users_role_id'); 
        });

        Schema::table('role_permission', function (Blueprint $table) {
            $table->index('role_id', 'idx_role_permissions_role_id'); 
            $table->index('permission_id', 'idx_role_permissions_permission_id'); 
        });


        Schema::table('permissions', function (Blueprint $table) {
            $table->index('name', 'idx_permissions_name'); 
        });

        
        Schema::table('roles', function (Blueprint $table) {
            $table->index('name', 'idx_roles_name'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_role_id');
        });

        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropIndex('idx_role_permissions_role_id');
            $table->dropIndex('idx_role_permissions_permission_id');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex('idx_permissions_name');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex('idx_roles_name');
        });
    }
};
