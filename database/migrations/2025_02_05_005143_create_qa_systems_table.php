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
        Schema::create('qa_systems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('question');
            $table->json('options');
            $table->enum('answer_type', ['single', 'multiple']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_systems');
    }
};
