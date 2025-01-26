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
        Schema::create('document_record_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedInteger('record_id');
            $table->foreign('record_id')->references('record_id')->on('document_records')->onDelete('cascade');
            $table->unsignedInteger('author_id');
            $table->string('author',255);
            $table->string('message',255);  
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent(); 
        });

        DB::statement("ALTER TABLE `document_record_messages` comment 'Listado de mensajes de los usuarios relacionados al registro'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_record_messages');
    }
};
