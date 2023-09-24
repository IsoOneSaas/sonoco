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
        $tableName = 'document_status';

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('status_id');
            $table->unsignedInteger('document_id');
            $table->foreign('document_id')->references('document_id')->on('documents')->onDelete('cascade');            

            $table->string('action',16);
            $table->timestamp('action_date')->useCurrent();
            $table->string('action_by',36);
            
            $table->timestamp('delivery_date')->nullable();

            $table->timestamp('return_date')->useCurrent();
            $table->string('return_by',36)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        DB::statement("ALTER TABLE `$tableName` comment 'Define el estado del documento'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_status');
    }
};
