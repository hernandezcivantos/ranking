<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('group_matches', function (Blueprint $table) {
            $table->foreignId('player1_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('player2_id')->nullable()->constrained('players')->nullOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('group_matches', function (Blueprint $table) {
            $table->dropForeign(['player1_id']);
            $table->dropForeign(['player2_id']);
            $table->dropColumn(['player1_id', 'player2_id']);
        });
    }
};
