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
            $table->unsignedTinyInteger('sets_per_match')->default(3);
            $table->unsignedTinyInteger('fields_total')->default(2);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matchdays', function (Blueprint $table) {
            $table->dropColumn([
                'sets_per_match',
                'total_fields'
            ]);
        });
    }
};
