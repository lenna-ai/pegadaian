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
        Schema::create('help_desks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_code');
            $table->string('branch_name');
            $table->string('branch_name_staff');
            $table->string('branch_phone_number');
            $table->string('date_to_call');
            $table->unsignedInteger('call_duration');
            $table->string('result_call');
            $table->string('name_agent');
            $table->string('input_voice_call');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_desks');
    }
};
