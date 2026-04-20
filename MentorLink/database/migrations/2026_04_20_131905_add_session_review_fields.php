<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter des champs aux sessions pour le suivi
        Schema::table('mentor_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('mentor_sessions', 'is_reviewed')) {
                $table->boolean('is_reviewed')->default(false);
            }
            if (!Schema::hasColumn('mentor_sessions', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });

        // Ajouter un champ de pénalité aux reviews
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'is_late_cancellation_penalty')) {
                $table->boolean('is_late_cancellation_penalty')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('mentor_sessions', function (Blueprint $table) {
            $table->dropColumn(['is_reviewed', 'completed_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_late_cancellation_penalty']);
        });
    }
};