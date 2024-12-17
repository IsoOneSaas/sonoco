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
        Schema::create('document_record_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();            
            $table->unsignedInteger('user_id');        
            $table->unsignedInteger('record_id');
            $table->foreign('record_id')->references('record_id')->on('document_records')->onDelete('cascade');
            $table->string('name',255);
            $table->string('job',255);
            $table->unsignedTinyInteger('auth')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent(); 
        });

        DB::statement("ALTER TABLE `document_record_users` comment 'Relación de usuarios con el registro'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_record_users');
    }
};
