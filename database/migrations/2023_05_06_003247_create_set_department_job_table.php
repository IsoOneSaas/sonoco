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
        Schema::create('set_department_job', function (Blueprint $table) {
            $table->id();

            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('department_id')->on('set_departments')->onDelete('cascade');  

            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')->references('job_id')->on('set_jobs')->onDelete('cascade');
        
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_department_job');
    }
};
