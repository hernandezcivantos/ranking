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
        Schema::table('matchdays', function (Blueprint $table) {
            $table->string('round_type')->nullable();
            $table->unsignedTinyInteger('qualified_per_group')->nullable();
            $table->string('sport')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            $table->dropColumn([
                'round_type',
                'qualified_per_group',
                'sport'
            ]);
        });
    }
};
