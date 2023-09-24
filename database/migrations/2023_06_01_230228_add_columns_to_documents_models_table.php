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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('status', 12)->default('CREATED')->after('filename');
            $table->string('flow', 8)->default('AUTO')->after('status');
            $table->string('pattern', 8)->default('HTML')->after('flow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('flow');
            $table->dropColumn('pattern');
        });
    }
};
