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
        Schema::table('logic_changes', function (Blueprint $table) {
            // Tornar unit_id nullable
            $table->foreignId('unit_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logic_changes', function (Blueprint $table) {
            // Reverter para não nullable
            $table->foreignId('unit_id')->nullable(false)->change();
        });
    }
};
