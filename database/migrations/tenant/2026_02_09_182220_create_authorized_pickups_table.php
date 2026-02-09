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
        Schema::create('authorized_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('authorized_person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('authorized_name'); // in case person not registered
            $table->foreignId('relationship_id')->nullable()->constrained('relationships')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['child_id', 'is_active']);
            $table->index('authorized_person_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authorized_pickups');
    }
};
