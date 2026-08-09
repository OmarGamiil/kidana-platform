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
        Schema::create('activation_invites', function (Blueprint $table) {
            $table->id();
            // Route-model-binding key — safe to put in a URL, unlike the raw token.
            $table->uuid('public_id')->unique();
            $table->foreignId('office_season_id')->constrained()->cascadeOnDelete();
            // hash_hmac('sha256', $rawToken, config('app.key')), compared with
            // hash_equals() — the raw token is high-entropy and never stored.
            $table->string('token_hash');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['office_season_id', 'used_at', 'revoked_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_invites');
    }
};
