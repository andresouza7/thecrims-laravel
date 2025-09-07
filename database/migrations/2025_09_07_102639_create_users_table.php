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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('avatar')->nullable();
            $table->string('nationality')->nullable();

            $table->foreignId('career_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('career_level_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('cash')->default(0);
            $table->unsignedBigInteger('bank')->default(0);
            $table->integer('health')->default(0);
            $table->integer('max_health')->default(0);
            $table->integer('stamina')->default(0);
            $table->integer('addiction')->default(0);
            $table->unsignedBigInteger('intelligence')->default(0);
            $table->unsignedBigInteger('strength')->default(0);
            $table->unsignedBigInteger('charisma')->default(0);
            $table->unsignedBigInteger('tolerance')->default(0);
            $table->unsignedBigInteger('respect')->default(0);

            $table->unsignedBigInteger('single_robbery_power')->default(0);
            $table->unsignedBigInteger('gang_robbery_power')->default(0);
            $table->unsignedBigInteger('assault_power')->default(0);
            $table->unsignedBigInteger('single_robbery_count')->default(0);
            $table->unsignedBigInteger('gang_robbery_count')->default(0);

            $table->unsignedBigInteger('drug_profits')->default(0);
            $table->unsignedBigInteger('hooker_profits')->default(0);
            $table->unsignedBigInteger('boat_profits')->default(0);
            $table->unsignedBigInteger('factory_profits')->default(0);

            $table->unsignedBigInteger('available_stats')->default(0);
            $table->integer('dealer_transactions')->default(0);
            $table->integer('tickets')->default(0);
            $table->timestamp('jail_end_time')->nullable();
            $table->timestamp('hire_thieves_cooldown')->nullable();

            $table->json('equipment_ids')->nullable();
            $table->foreignId('armor_id')->nullable()->constrained('equipment')->nullOnDelete();
            $table->foreignId('weapon_id')->nullable()->constrained('equipment')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
