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
        Schema::table('document_records', function (Blueprint $table) {
            $table->string('code', 255)->nullable()->after('status');
            $table->string('year', 4)->nullable()->after('code');
            $table->unsignedInteger('serial')->nullable()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_records', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('year');
            $table->dropColumn('serial');
        });
    }
};
