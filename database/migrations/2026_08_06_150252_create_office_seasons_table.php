<?php

use App\Enums\OfficeSeasonStatus;
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
        Schema::create('office_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->restrictOnDelete();
            $table->foreignId('season_id')->constrained()->restrictOnDelete();
            $table->string('delegate_number');
            $table->string('status')->default(OfficeSeasonStatus::PendingActivation->value);
            $table->string('official_email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->string('terms_version')->nullable();
            $table->timestamp('terms_accepted_at')->nullable();
            $table->string('terms_accepted_ip')->nullable();
            // The primary member for THIS season only — a person can be primary in
            // one season and not another, so this does not live on the permanent
            // office_memberships row. A single nullable FK also guarantees at most
            // one primary member per season by construction.
            $table->foreignId('primary_membership_id')->nullable()
                ->constrained('office_memberships')->nullOnDelete();
            $table->timestamps();

            $table->unique(['office_id', 'season_id']);
            $table->unique(['season_id', 'delegate_number']);
            $table->index(['season_id', 'status']);
            $table->index(['office_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_seasons');
    }
};
