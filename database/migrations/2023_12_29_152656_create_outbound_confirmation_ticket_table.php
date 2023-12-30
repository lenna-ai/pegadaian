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
        Schema::create('outbound_confirmation_ticket', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id')->default(0);
            $table->string('name_agent');
            $table->string('ticket_number');
            $table->string('category');
            $table->string('status');
            $table->string('call_time');
            $table->unsignedInteger('call_duration');
            $table->string('result_call');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_confirmation_ticket');
    }
};
