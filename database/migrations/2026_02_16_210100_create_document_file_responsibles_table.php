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
        Schema::create('document_file_responsibles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('job_id');
            $table->json('users')->nullable();
            $table->unsignedInteger('admin_id');
            $table->tinyInteger('auth')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();  
        });

        DB::statement("ALTER TABLE `document_file_responsibles` comment 'Autorizaciones para afectar la inforamción de los archivos'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_file_responsibles');
    }
};
