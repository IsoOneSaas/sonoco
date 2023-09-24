<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('set_admin_system', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('user_id')->on('set_users')->onDelete('cascade');  

            $table->integer('system_id')->unsigned()->index();
            $table->foreign('system_id')->references('system_id')->on('set_systems')->onDelete('cascade');
        
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_admin_system');
    }
};
