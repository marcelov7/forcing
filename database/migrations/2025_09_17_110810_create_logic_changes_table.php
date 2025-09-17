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
        Schema::create('logic_changes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('solicitante');
            $table->text('descricao_alteracao');
            $table->string('departamento');
            $table->date('data_solicitacao_form');
            $table->text('motivo_alteracao');
            $table->boolean('termo_aceito')->default(false);
            
            $table->enum('status', [
                'pendente', 
                'em_analise', 
                'aprovado', 
                'rejeitado', 
                'em_execucao', 
                'concluido', 
                'cancelado'
            ])->default('pendente');
            
            // Relacionamentos
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null');
            
            // Campos de validação/aprovação
            $table->foreignId('gerente_manutencao_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('coordenador_manutencao_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('tecnico_especialista_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Campos de observações e controle
            $table->text('observacoes_tecnico')->nullable();
            $table->text('observacoes_gerente_manutencao')->nullable();
            $table->text('observacoes_coordenador_manutencao')->nullable();
            $table->text('observacoes_tecnico_especialista')->nullable();
            
            // Timestamps das aprovações
            $table->timestamp('aprovacao_gerente_manutencao')->nullable();
            $table->timestamp('aprovacao_solicitante')->nullable();
            $table->timestamp('aprovacao_coordenador_manutencao')->nullable();
            $table->timestamp('aprovacao_tecnico_especialista')->nullable();
            
            $table->json('arquivos_anexos')->nullable();
            
            // Timestamps de controle
            $table->timestamp('data_solicitacao')->useCurrent();
            $table->timestamp('data_analise')->nullable();
            $table->timestamp('data_execucao')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para melhor performance
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['tecnico_id', 'created_at']);
            $table->index(['unit_id', 'created_at']);
            $table->index(['departamento', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logic_changes');
    }
};
