<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alterar o ENUM do campo status para incluir 'implementado'
        DB::statement("ALTER TABLE `logic_changes` MODIFY COLUMN `status` ENUM(
            'pendente', 
            'em_analise', 
            'aprovado', 
            'rejeitado', 
            'em_execucao', 
            'implementado', 
            'concluido', 
            'cancelado'
        ) DEFAULT 'pendente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voltar ao ENUM original (remover 'implementado')
        // ATENÇÃO: Isso pode causar perda de dados se houver registros com status 'implementado'
        DB::statement("UPDATE `logic_changes` SET `status` = 'concluido' WHERE `status` = 'implementado'");
        
        DB::statement("ALTER TABLE `logic_changes` MODIFY COLUMN `status` ENUM(
            'pendente', 
            'em_analise', 
            'aprovado', 
            'rejeitado', 
            'em_execucao', 
            'concluido', 
            'cancelado'
        ) DEFAULT 'pendente'");
    }
};