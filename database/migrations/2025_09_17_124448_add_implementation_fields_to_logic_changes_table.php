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
            // Campos para implementação
            $table->timestamp('data_implementacao')->nullable()->after('aprovacao_tecnico_especialista');
            $table->text('observacoes_implementacao')->nullable()->after('data_implementacao');
            $table->foreignId('implementado_por_id')->nullable()->constrained('users')->onDelete('set null')->after('observacoes_implementacao');
            $table->timestamp('implementado_em')->nullable()->after('implementado_por_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logic_changes', function (Blueprint $table) {
            $table->dropForeign(['implementado_por_id']);
            $table->dropColumn([
                'data_implementacao',
                'observacoes_implementacao', 
                'implementado_por_id',
                'implementado_em'
            ]);
        });
    }
};