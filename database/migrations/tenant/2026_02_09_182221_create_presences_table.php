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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->foreignId('ministry_id')->nullable()->constrained('ministries')->nullOnDelete();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('presence_method_id')->constrained('presence_methods')->onDelete('cascade');
            $table->timestamp('registered_at');
            $table->timestamp('checked_out_at')->nullable();
            $table->integer('duration_minutes')->nullable(); // for online meetings
            $table->boolean('is_serving')->default(false); // true if person is serving, not participating
            $table->timestamps();
            $table->softDeletes();

            $table->index(['person_id', 'registered_at']);
            $table->index(['event_id', 'registered_at']);
            $table->index(['group_id', 'registered_at']);
            $table->index(['ministry_id', 'registered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
