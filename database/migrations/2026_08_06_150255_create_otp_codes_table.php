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
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activation_invite_id')->constrained()->cascadeOnDelete();
            $table->string('purpose')->default('activation_phone_verification');
            $table->string('channel')->default('sms');
            $table->string('recipient');
            $table->string('code_hash');
            $table->timestamp('sent_at');
            $table->timestamp('expires_at');
            $table->timestamp('last_attempt_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamp('invalidated_at')->nullable();
            $table->timestamps();

            $table->index(['activation_invite_id', 'invalidated_at', 'consumed_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
