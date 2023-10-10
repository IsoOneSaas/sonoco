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
        $tableName = 'set_job_process';

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();

            $table->integer('process_id')->unsigned()->index();
            $table->foreign('process_id')->references('process_id')->on('set_processes')->onDelete('cascade');

            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')->references('job_id')->on('set_jobs')->onDelete('cascade'); 
            
            $table->tinyInteger('auth')->unsigned()->default(0);
        
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Relación de cargos con procesos : permisos de proceso'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_job_process');
    }
};
