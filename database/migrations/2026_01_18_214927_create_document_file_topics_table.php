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
        Schema::create('document_file_topics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('topic_id');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('department_id')->on('set_departments')->onDelete('cascade');
            $table->string('code',16);
            $table->string('name',48); 
            $table->string('description',255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();  
        });

        DB::statement("ALTER TABLE `document_file_topics` comment 'Listado de temas del archivo de registros'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_file_topics');
    }
};
