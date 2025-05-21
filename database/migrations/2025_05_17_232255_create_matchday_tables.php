<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo crear las tablas que aún no existen según tus otras migraciones

        Schema::create('group_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matchday_group_id')->constrained('matchday_groups')->onDelete('cascade');
            $table->string('player1_name');
            $table->string('player2_name');
            $table->string('winner_name')->nullable();
            $table->timestamps();
        });

        Schema::create('brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matchday_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('bracket_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bracket_id')->constrained()->onDelete('cascade');
            $table->integer('number');
            $table->timestamps();
        });

        Schema::create('bracket_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bracket_round_id')->constrained()->onDelete('cascade');
            $table->string('player1_name');
            $table->string('player2_name');
            $table->string('winner_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bracket_matches');
        Schema::dropIfExists('bracket_rounds');
        Schema::dropIfExists('brackets');
        Schema::dropIfExists('group_matches');
    }
};
