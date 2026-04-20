<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('penalty_amount', 3, 2)->default(0.5); // Montant de la pénalité (ex: 0.5 étoile)
            $table->string('reason'); // Raison de la pénalité
            $table->text('description')->nullable(); // Description détaillée
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_penalties');
    }
};