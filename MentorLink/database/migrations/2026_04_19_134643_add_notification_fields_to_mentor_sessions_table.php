<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentor_sessions', function (Blueprint $table) {
            $table->boolean('notification_1h_sent')->default(false);
            $table->boolean('notification_5m_sent')->default(false);
            $table->text('session_notes')->nullable();
            $table->string('meeting_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mentor_sessions', function (Blueprint $table) {
            $table->dropColumn(['notification_1h_sent', 'notification_5m_sent', 'session_notes', 'meeting_link']);
        });
    }
};