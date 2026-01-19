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
        Schema::create('document_file_subtopics', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('subtopic_id');
            $table->unsignedInteger('topic_id');
            $table->foreign('topic_id')->references('topic_id')->on('document_file_topics')->onDelete('cascade');
            $table->string('code',16);
            $table->string('name',48); 
            $table->string('description',255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();  
        });

        DB::statement("ALTER TABLE `document_file_subtopics` comment 'Listado de subtemas del archivo de registros'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_file_subtopics');
    }
};
