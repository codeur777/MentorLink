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
        Schema::create('mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('headline');
            $table->text('bio')->nullable();
            $table->string('focus_area')->nullable();
            $table->string('availability_note')->nullable();
            $table->string('session_format')->nullable();
            $table->json('expertise_tags')->nullable();
            $table->unsignedInteger('years_experience')->default(0);
            $table->boolean('is_listed')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_profiles');
    }
};
