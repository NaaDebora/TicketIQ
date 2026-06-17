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
        Schema::create('ticket_ai_analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();

            $table->string('category_suggested')->nullable();
            $table->string('priority_suggested')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->json('raw_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_ai_analyses');
    }
};
