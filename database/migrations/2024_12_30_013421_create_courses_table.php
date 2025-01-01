<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('price_sale', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->float('duration')->default(0);
            $table->enum('level', [Course::LEVEL_BEGINNER, Course::LEVEL_INTERMEDIATE, Course::LEVEL_ADVANCED])->default(Course::LEVEL_BEGINNER);
            $table->integer('total_student')->default(0);
            $table->json('requirement')->nullable();
            $table->json('benefits')->nullable();
            $table->json('qa')->nullable();
            $table->enum('status', [Course::STATUS_DRAFT, Course::STATUS_PENDING, Course::STATUS_APPROVED, Course::STATUS_REJECTED])->default(Course::STATUS_DRAFT);
            $table->date('accepted')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
