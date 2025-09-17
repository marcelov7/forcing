<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogicChange extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'solicitante',
        'descricao_alteracao',
        'departamento',
        'data_solicitacao_form',
        'motivo_alteracao',
        'termo_aceito',
        'status',
        'user_id',
        'tecnico_id',
        'unit_id',
        'gerente_manutencao_id',
        'coordenador_manutencao_id',
        'tecnico_especialista_id',
        'observacoes_tecnico',
        'observacoes_gerente_manutencao',
        'observacoes_coordenador_manutencao',
        'observacoes_tecnico_especialista',
        'aprovacao_gerente_manutencao',
        'aprovacao_solicitante',
        'aprovacao_coordenador_manutencao',
        'aprovacao_tecnico_especialista',
        'data_solicitacao',
        'data_analise',
        'data_execucao',
        'data_conclusao',
        'arquivos_anexos',
        'data_implementacao',
        'observacoes_implementacao',
        'implementado_por_id',
        'implementado_em',
    ];

    protected $casts = [
        'data_solicitacao' => 'datetime',
        'data_solicitacao_form' => 'date',
        'data_analise' => 'datetime',
        'data_execucao' => 'datetime',
        'data_conclusao' => 'datetime',
        'aprovacao_gerente_manutencao' => 'datetime',
        'aprovacao_solicitante' => 'datetime',
        'aprovacao_coordenador_manutencao' => 'datetime',
        'aprovacao_tecnico_especialista' => 'datetime',
        'termo_aceito' => 'boolean',
        'arquivos_anexos' => 'array',
        'data_implementacao' => 'datetime',
        'implementado_em' => 'datetime',
    ];

    /**
     * Status possíveis para alteração de lógica
     */
    const STATUS_PENDENTE = 'pendente';
    const STATUS_EM_ANALISE = 'em_analise';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_REJEITADO = 'rejeitado';
    const STATUS_EM_EXECUCAO = 'em_execucao';
    const STATUS_IMPLEMENTADO = 'implementado';
    const STATUS_CONCLUIDO = 'concluido';
    const STATUS_CANCELADO = 'cancelado';

    /**
     * Departamentos disponíveis
     */
    const DEPARTAMENTOS = [
        'Manutenção',
        'Produção',
        'Qualidade',
        'Engenharia',
        'Operações',
        'Automação',
        'Elétrica',
        'Mecânica',
        'Instrumentação'
    ];

    /**
     * Relacionamento com usuário solicitante
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com técnico responsável
     */
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /**
     * Relacionamento com unidade
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relacionamento com gerente de manutenção
     */
    public function gerenteManutencao()
    {
        return $this->belongsTo(User::class, 'gerente_manutencao_id');
    }

    /**
     * Relacionamento com coordenador de manutenção
     */
    public function coordenadorManutencao()
    {
        return $this->belongsTo(User::class, 'coordenador_manutencao_id');
    }

    /**
     * Relacionamento com técnico especialista
     */
    public function tecnicoEspecialista()
    {
        return $this->belongsTo(User::class, 'tecnico_especialista_id');
    }

    /**
     * Relacionamento com quem implementou
     */
    public function implementadoPor()
    {
        return $this->belongsTo(User::class, 'implementado_por_id');
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por departamento
     */
    public function scopeDepartamento($query, $departamento)
    {
        return $query->where('departamento', $departamento);
    }

    /**
     * Verifica se pode ser editado
     */
    public function podeSerEditado(): bool
    {
        return in_array($this->status, [self::STATUS_PENDENTE, self::STATUS_EM_ANALISE]);
    }

    /**
     * Verifica se pode ser cancelado
     */
    public function podeSerCancelado(): bool
    {
        return !in_array($this->status, [self::STATUS_IMPLEMENTADO, self::STATUS_CONCLUIDO, self::STATUS_CANCELADO]);
    }

    /**
     * Verifica se pode ser marcado como implementado
     */
    public function podeSerImplementado(): bool
    {
        // Só pode ser implementado se todas aprovações foram concedidas e ainda não foi implementado
        return $this->todasAprovacoesCompletas() && 
               !in_array($this->status, [self::STATUS_IMPLEMENTADO, self::STATUS_CONCLUIDO, self::STATUS_CANCELADO]);
    }

    /**
     * Get status com formatação
     */
    public function getStatusFormatado(): string
    {
        $status = [
            self::STATUS_PENDENTE => 'Pendente',
            self::STATUS_EM_ANALISE => 'Em Análise',
            self::STATUS_APROVADO => 'Aprovado',
            self::STATUS_REJEITADO => 'Rejeitado',
            self::STATUS_EM_EXECUCAO => 'Em Execução',
            self::STATUS_IMPLEMENTADO => 'Implementado',
            self::STATUS_CONCLUIDO => 'Concluído',
            self::STATUS_CANCELADO => 'Cancelado',
        ];

        return $status[$this->status] ?? 'Desconhecido';
    }

    /**
     * Verifica se todas as aprovações foram concedidas
     */
    public function todasAprovacoesCompletas(): bool
    {
        return $this->aprovacao_gerente_manutencao &&
               $this->aprovacao_coordenador_manutencao &&
               $this->aprovacao_tecnico_especialista;
    }

    /**
     * Get lista de aprovadores pendentes
     */
    public function getAprovadoresPendentes(): array
    {
        $pendentes = [];
        
        if (!$this->aprovacao_gerente_manutencao) {
            $pendentes[] = 'Gerente de Manutenção';
        }
        
        
        if (!$this->aprovacao_coordenador_manutencao) {
            $pendentes[] = 'Coordenador de Manutenção';
        }
        
        if (!$this->aprovacao_tecnico_especialista) {
            $pendentes[] = 'Técnico Especialista em Automação';
        }
        
        return $pendentes;
    }

    /**
     * Get classe CSS para status
     */
    public function getStatusClass(): string
    {
        $classes = [
            self::STATUS_PENDENTE => 'bg-warning text-dark',
            self::STATUS_EM_ANALISE => 'bg-info text-white',
            self::STATUS_APROVADO => 'bg-success text-white',
            self::STATUS_REJEITADO => 'bg-danger text-white',
            self::STATUS_EM_EXECUCAO => 'bg-primary text-white',
            self::STATUS_IMPLEMENTADO => 'bg-success text-white',
            self::STATUS_CONCLUIDO => 'bg-success text-white',
            self::STATUS_CANCELADO => 'bg-secondary text-white',
        ];

        return $classes[$this->status] ?? 'bg-secondary text-white';
    }

    /**
     * Get porcentagem de aprovações completas
     */
    public function getPorcentagemAprovacoes(): int
    {
        $total = 3; // Total de aprovações necessárias
        $completas = 0;
        
        if ($this->aprovacao_gerente_manutencao) $completas++;
        if ($this->aprovacao_coordenador_manutencao) $completas++;
        if ($this->aprovacao_tecnico_especialista) $completas++;
        
        return round(($completas / $total) * 100);
    }
}
