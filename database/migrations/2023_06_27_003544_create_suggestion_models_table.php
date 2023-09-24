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
        $tableName = 'document_suggestions';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('suggestion_id');
            $table->string('user_uid', 36);
            $table->unsignedInteger('system_id');
            $table->string('document', 255);
            $table->text('justification');

            $table->string('name', 255)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('mimetype', 128)->nullable();
            $table->integer('size')->nullable();

            $table->unsignedTinyInteger('status')->default(0); 
                                                   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Listado de sugerencias para nuevos documentos'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_suggestions');
    }
};
