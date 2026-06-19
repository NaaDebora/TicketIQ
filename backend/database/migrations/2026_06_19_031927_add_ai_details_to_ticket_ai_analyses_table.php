<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_ai_analyses', function (Blueprint $table) {
            $table->text('possible_cause')->nullable()->after('confidence');
            $table->text('suggested_solution')->nullable()->after('possible_cause');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_ai_analyses', function (Blueprint $table) {
            $table->dropColumn([
                'possible_cause',
                'suggested_solution',
            ]);
        });
    }
};
