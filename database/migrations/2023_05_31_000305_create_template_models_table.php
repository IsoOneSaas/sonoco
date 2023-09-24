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
        $tableName = 'document_templates';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('template_id');
            $table->string('name',255)->unique();
            $table->string('label',64)->nullable();
            $table->string('description',255);
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Listado de plantillas para ser relacionadas al tipo de documento'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
