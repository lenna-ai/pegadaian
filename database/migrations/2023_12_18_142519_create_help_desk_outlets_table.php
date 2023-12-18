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
        Schema::create('help_desk_outlets', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_name');
            $table->unsignedBigInteger('branch_code');
            $table->string('status');
            $table->string('parent_branch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_desk_outlets');
    }
};
