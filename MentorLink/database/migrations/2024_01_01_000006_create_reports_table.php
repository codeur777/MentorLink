<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reported_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('mentor_sessions')->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamps();

            // One report per session per reporter
            $table->unique(['reporter_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
