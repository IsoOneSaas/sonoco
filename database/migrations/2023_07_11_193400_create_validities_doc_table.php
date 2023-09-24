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
        $tableName = 'document_validities_doc';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('validity_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');
            $table->unsignedSmallInteger('expiration_value');
            $table->string('expiration_text', 16);                          
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Relación de validaciones por documento específico'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_validities_doc');
    }
};
