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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // gender, age_range, modality, cycle, mobility, profile, campus_restriction, capacity, prerequisite
            $table->json('options')->nullable(); // available values for this feature
            $table->boolean('is_achievement')->default(false); // if true, completing generates achievement
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
