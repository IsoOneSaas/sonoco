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
        $tableName = 'document_contents';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('content_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');
            $table->unsignedTinyInteger('version');
            $table->string('label',64)->nullable();
            $table->mediumText('content');                      // > 17K chars, 16Mb < 4.3M chars, 4 GB
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Contenido de los documentos con diseño HTML'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_contents', function (Blueprint $table) {
            //
        });
    }
};
