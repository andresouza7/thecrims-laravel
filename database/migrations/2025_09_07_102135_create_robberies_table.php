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
        Schema::create('robberies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('required_power');
            $table->integer('required_stamina');
            $table->string('type');
            $table->integer('cash');
            $table->json('drugs');
            $table->json('components');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robberies');
    }
};
