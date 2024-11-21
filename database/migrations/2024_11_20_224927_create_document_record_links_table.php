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
        Schema::create('document_record_links', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('link_id');
            $table->unsignedInteger('record_id');
            $table->foreign('record_id')->references('record_id')->on('document_records')->onDelete('cascade');
            $table->string('name',255);
            $table->string('link',255);
            $table->string('type',64);
            $table->string('size',12);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `document_record_links` comment 'Relación de archivos anexos al registro'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_record_links');
    }
};
