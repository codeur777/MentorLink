<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'average_rating')) {
                $table->decimal('average_rating', 3, 2)->default(0)->after('role');
            }
            if (!Schema::hasColumn('users', 'total_reviews')) {
                $table->integer('total_reviews')->default(0)->after('average_rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'total_reviews']);
        });
    }
};