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
        $tableName = 'document_records';

        Schema::create('document_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('record_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');
            $table->string('name',255);
            $table->mediumText('content')->nullable();                      // > 17K chars, 16Mb < 4.3M chars, 4 GB
            $table->unsignedInteger('author_id');
            $table->string('author_name',255);
            $table->string('author_position',255)->nullable(); 
            $table->string('filename',255)->nullable(); 
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        
        DB::statement("ALTER TABLE `$tableName` comment 'Listado de registros de los documentos'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_records');
    }
};
