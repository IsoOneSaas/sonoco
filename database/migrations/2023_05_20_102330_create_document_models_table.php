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
        $tableName = 'documents';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('document_id');
            $table->unsignedInteger('system_id');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('department_id')->on('set_departments')->onDelete('cascade');
            $table->unsignedInteger('process_id');
            //$table->foreign('process_id')->references('process_id')->on('set_processes')->onDelete('cascade');
            $table->unsignedInteger('location_id');
            //$table->foreign('location_id')->references('location_id')->on('set_locations')->onDelete('cascade');
            $table->unsignedInteger('type_id');

            $table->string('code',24); 
            $table->string('name',255);
            $table->tinyInteger('version');
            $table->smallInteger('serial')->default(1);

            $table->json('job_edit_id');
            $table->json('job_review_id');
            $table->json('job_approve_id');

            $table->string('filename',255)->nullable();
            $table->json('settings')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Listado de documentos'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
