<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. You need to install doctrine/dbal to make this work: >composer require doctrine/dbal
     */
    public function up(): void
    {
        Schema::table('document_record_topics', function (Blueprint $table) {
            $table->unsignedInteger('topic')->change();
            $table->unsignedInteger('subject')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_record_topics', function (Blueprint $table) {
            $table->string('topic', 255)->change();
            $table->string('subject', 255)->change();
        });
    }
};
