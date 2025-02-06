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
        if (Schema::hasColumn('careers', 'certificates') && Schema::hasColumn('careers', 'qa_systems')) {
            Schema::table('careers', function (Blueprint $table) {
                $table->dropColumn(['certificates', 'qa_systems']);
            });
        }

        if (!Schema::hasColumn('profiles', 'certificates') && !Schema::hasColumn('profiles', 'qa_systems')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->json('certificates')->nullable()->after('bio');
                $table->json('qa_systems')->nullable()->after('certificates');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('profiles', 'certificates') && Schema::hasColumn('profiles', 'qa_systems')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->dropColumn(['certificates', 'qa_systems']);
            });
        }

        if (!Schema::hasColumn('careers', 'certificates') && !Schema::hasColumn('careers', 'qa_systems')) {
            Schema::table('careers', function (Blueprint $table) {
                $table->json('certificates')->nullable();
                $table->json('qa_systems')->nullable();
            });
        }
    }
};
