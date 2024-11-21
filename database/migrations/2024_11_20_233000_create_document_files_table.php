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
        Schema::create('document_files', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('file_id');
            $table->unsignedInteger('system_id');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('department_id')->on('set_departments')->onDelete('cascade');
            $table->unsignedInteger('document_id')->default(0);
            $table->unsignedInteger('record_id');
            $table->foreign('record_id')->references('record_id')->on('document_records')->onDelete('cascade');            
            $table->unsignedInteger('job_id');
            $table->string('code',24)->nullable(); 
            $table->string('name',255);
            $table->tinyInteger('support')->default(0);
            $table->string('storage',255)->nullable();
            $table->string('classification',255)->nullable();           
            $table->unsignedInteger('index_id')->default(0);
            $table->unsignedInteger('disposal_id')->default(0);

            $table->date('dwell_date')->nullable();
            $table->smallInteger('dwell_value')->nullable();
            $table->string('dwell_frequency',50)->nullable(); 
            $table->date('dead_date')->nullable();
            $table->smallInteger('dead_value')->nullable();
            $table->string('dead_frequency',50)->nullable();             
            $table->smallInteger('hold_value')->nullable();
            $table->string('hold_frequency',50)->nullable();

            $table->integer('serial')->default(0);
            $table->text('settings')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();            
        });

        DB::statement("ALTER TABLE `document_files` comment 'Listado de archivado de registros'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_files');
    }
};
