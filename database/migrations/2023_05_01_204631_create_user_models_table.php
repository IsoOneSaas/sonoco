<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		$tableName = 'set_users';
		
        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('user_id');
            $table->uuid('user_uid');
            $table->string('name',255);
            $table->string('email',255)->unique();
            $table->string('password',255);
            $table->string('role',16);            
            $table->json('options');
            $table->boolean('is_active')->default(0);
            $table->string('remember_token',100)->nullable();       
            $table->timestamp('email_verified_at');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
			$table->timestamp('deleted_at')->nullable();
        });
		
        DB::statement("ALTER TABLE `$tableName` comment 'Listado de usuarios para el control de acceso a la aplicación'");
		
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_users');
    }
};
