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
        Schema::create('game_params', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('type');
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            // $table->morphs('related'); // cria related_id e related_type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_params');
    }
};
