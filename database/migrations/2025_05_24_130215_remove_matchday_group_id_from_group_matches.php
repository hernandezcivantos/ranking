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
            $table->dropForeign(['matchday_group_id']); // si era clave foránea
            $table->dropColumn('matchday_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('group_matches', function (Blueprint $table) {
            $table->foreignId('matchday_group_id')->constrained()->onDelete('cascade');
        });
    }
};
