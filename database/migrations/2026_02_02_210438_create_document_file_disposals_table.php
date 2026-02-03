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
        Schema::create('document_file_disposals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('disposal_id');
            $table->string('name',255)->unique(); 
            $table->string('description',255)->nullable();            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();  
        });

         DB::statement("ALTER TABLE `document_file_disposals` comment 'Listado de opciones para la disposición final del archivo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_file_disposals');
    }
};
