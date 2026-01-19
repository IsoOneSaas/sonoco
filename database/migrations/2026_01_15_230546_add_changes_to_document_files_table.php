<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_files', function (Blueprint $table) {
            $table->unsignedInteger('location_id')->after('system_id');
            $table->unsignedInteger('process_id')->after('department_id');
            $table->dropColumn('document_id');
            // ALTER TABLE `document_files` DROP FOREIGN KEY `document_files_record_id_foreign`;
            $table->dropColumn('record_id');
            $table->unsignedInteger('topic_id')->after('process_id');
            $table->unsignedInteger('subtopic_id')->after('topic_id');
            $table->string('code', 255)->nullable(false)->unique()->change();
            $table->dropColumn('serial');
            $table->dropColumn('settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_files', function (Blueprint $table) {
            $table->dropColumn('location_id');
            $table->dropColumn('process_id');
            $table->unsignedInteger('document_id')->default(0);
            $table->unsignedInteger('record_id');
            $table->foreign('record_id')->references('record_id')->on('document_records')->onDelete('cascade');  
            $table->dropColumn('topic_id');
            $table->dropColumn('subtopic_id');
            $table->dropColumn('code');
            $table->string('code',24)->nullable(); 
            $table->integer('serial')->default(0);
            $table->text('settings')->nullable();
        });
    }
};
