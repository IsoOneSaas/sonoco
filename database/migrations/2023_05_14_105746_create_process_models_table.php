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
        Schema::create('set_processes', function (Blueprint $table) {
            $table->increments('process_id');
            $table->integer('job_id');
            $table->string('code',8)->unique(); 
            $table->string('name',64)->unique();
            $table->string('version',24);
            $table->text('target');            
            $table->text('requirement_client')->nullable();  
            $table->text('requirement_company')->nullable();  
            $table->text('requirement_legal')->nullable();  
            $table->text('sources')->nullable();  
            $table->text('risk_client')->nullable();  
            $table->text('risk_company')->nullable();  
            $table->text('risk_legal')->nullable();  
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_processes');
    }
};
