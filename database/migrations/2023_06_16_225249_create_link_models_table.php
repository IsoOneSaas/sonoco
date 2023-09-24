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
        $tableName = 'document_links';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('link_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');
            $table->unsignedTinyInteger('version');
            $table->unsignedTinyInteger('content_id')->default(0);
            $table->string('name', 255);
            $table->string('link', 255);
            $table->string('type', 255);
            $table->string('size', 12);                                 
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Relación de archivos anexos a los documentos'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_links');
    }
};
