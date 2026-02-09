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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ministry_id')->constrained('ministries')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('requesting_ministry_id')->constrained('ministries')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->integer('volunteers_needed')->default(1);
            $table->timestamp('needed_at')->nullable();
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ministry_id', 'status_id']);
            $table->index(['requesting_ministry_id', 'status_id']);
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
