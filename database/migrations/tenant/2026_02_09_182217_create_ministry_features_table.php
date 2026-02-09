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
        Schema::create('ministry_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ministry_id')->constrained('ministries')->onDelete('cascade');
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
            $table->json('value'); // the selected value(s) for this feature
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ministry_id', 'feature_id']);
            $table->index('feature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministry_features');
    }
};
