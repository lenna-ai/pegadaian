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
        Schema::table('operators', function(Blueprint $table) {
            $table->string('input_voice_call')->nullable()->after('tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('operators', 'input_voice_call')) {
            Schema::table('operators', function(Blueprint $table) {
                $table->dropColumnIfExists('input_voice_call');
            });
        }
    }
};
