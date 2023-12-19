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
        Schema::table('help_desks', function (Blueprint $table) {
            $table->string('status')->default('Cabang')->nullable();
            $table->string('parent_branch')->default('CP MEDAN UTAMA')->nullable();
            $table->string('category')->default('A')->nullable();
            $table->string('tag')->default('A')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('help_desks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('parent_branch');
            $table->dropColumn('category');
            $table->dropColumn('tag');
        });
    }
};
