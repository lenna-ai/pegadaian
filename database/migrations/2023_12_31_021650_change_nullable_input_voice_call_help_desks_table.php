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
        Schema::table('help_desks', function(Blueprint $table) {
            $table->string('input_voice_call')->nullable()->change();
            $table->string('ticket_number')->default('ARIA')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('help_desks', function(Blueprint $table) {
            $table->string('input_voice_call')->change();

            if(Schema::hasColumn('help_desks', 'ticket_number')) {
                $table->dropColumn('ticket_number');
            }
        });
    }
};
