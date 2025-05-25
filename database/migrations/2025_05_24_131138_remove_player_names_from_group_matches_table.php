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
        Schema::table('group_matches', function (Blueprint $table) {
            if (Schema::hasColumn('group_matches', 'player1_name')) {
                $table->dropColumn('player1_name');
            }
            if (Schema::hasColumn('group_matches', 'player2_name')) {
                $table->dropColumn('player2_name');
            }
            if (Schema::hasColumn('group_matches', 'winner_name')) {
                $table->dropColumn('winner_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_matches', function (Blueprint $table) {
            $table->string('player1_name');
            $table->string('player2_name');
            $table->string('winner_name')->nullable();
        });
    }
};
