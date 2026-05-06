<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')->constrained();
            $table->foreignId('position_id')->constrained('player_positions');
            $table->foreignId('country_id')->constrained();

            $table->string('first_name');
            $table->string('last_name');
            $table->integer('age');
            $table->decimal('value', 12, 2);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
