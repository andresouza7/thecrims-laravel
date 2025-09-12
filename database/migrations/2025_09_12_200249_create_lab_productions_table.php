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
        Schema::create('lab_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_factory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->timestamp('ends_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_productions');
    }
};
