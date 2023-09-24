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
        Schema::create('set_admin_location', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('user_id')->on('set_users')->onDelete('cascade');  

            $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('location_id')->on('set_locations')->onDelete('cascade');
        
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_admin_location');
    }
};
