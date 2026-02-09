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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('action'); // created, updated, deleted, login, logout, etc.
            $table->string('model')->nullable(); // model class name
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('changes')->nullable(); // before/after
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('request_url')->nullable();
            $table->integer('response_code')->nullable();
            $table->timestamp('created_at'); // NO updated_at

            $table->index(['person_id', 'created_at']);
            $table->index(['model', 'model_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
