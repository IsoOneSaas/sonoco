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
        $tableName = 'document_disclaimers';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('disclaimer_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->string('action',16);                   
            $table->text('comment');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Contenido comentarios cuando se devuelve un documento'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_disclaimers');
    }
};
