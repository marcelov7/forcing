@extends('layouts.app')

@section('title', 'Alteração de Lógica #' . $logicChange->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-cogs"></i> Alteração de Lógica #{{ $logicChange->id }}</h4>
                    <span class="badge {{ $logicChange->getStatusClass() }} fs-6">
                        {{ $logicChange->getStatusFormatado() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $logicChange->titulo }}</h5>
                        <div class="mb-3">
                            <span class="badge bg-info me-2">{{ $logicChange->departamento }}</span>
                            <span class="badge bg-secondary">
                                Solicitado por: {{ $logicChange->solicitante }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted">Criado em {{ $logicChange->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user"></i> Solicitante</h6>
                        <p>{{ $logicChange->solicitante }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-building"></i> Departamento</h6>
                        <p>{{ $logicChange->departamento }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar"></i> Data da Solicitação</h6>
                        <p>{{ $logicChange->data_solicitacao_form ? $logicChange->data_solicitacao_form->format('d/m/Y') : 'Não informado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-check-circle"></i> Termo Aceito</h6>
                        <p>
                            @if($logicChange->termo_aceito)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Não</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6><i class="fas fa-align-left"></i> Descrição da Alteração</h6>
                    <p class="text-break">{{ $logicChange->descricao_alteracao }}</p>
                </div>

                <div class="mb-4">
                    <h6><i class="fas fa-question-circle"></i> Motivo da Alteração</h6>
                    <p class="text-break">{{ $logicChange->motivo_alteracao }}</p>
                </div>

                @if($logicChange->observacoes_tecnico)
                    <div class="mb-4">
                        <h6><i class="fas fa-comment"></i> Observações do Técnico</h6>
                        <div class="alert alert-info">
                            <p class="mb-0 text-break">{{ $logicChange->observacoes_tecnico }}</p>
                        </div>
                    </div>
                @endif

                <!-- Seção de Aprovações -->
                <div class="mb-4">
                    <h5 class="text-primary"><i class="fas fa-clipboard-check"></i> Status das Aprovações</h5>
                    
                    <div class="row">
                        <!-- Progresso das Aprovações -->
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Progresso: {{ $logicChange->getPorcentagemAprovacoes() }}% Completo</strong>
                                </div>
                                <div class="card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $logicChange->getPorcentagemAprovacoes() }}%">
                                            {{ $logicChange->getPorcentagemAprovacoes() }}%
                                        </div>
                                    </div>
                                    
                                    @if(!$logicChange->todasAprovacoesCompletas())
                                        <div class="alert alert-warning">
                                            <strong>Aprovações Pendentes:</strong>
                                            <ul class="mb-0">
                                                @foreach($logicChange->getAprovadoresPendentes() as $aprovador)
                                                    <li>{{ $aprovador }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Todas as aprovações foram concedidas!</strong>
                        </div>
                        
                        <!-- Botão Marcar como Implementado -->
                        @if($logicChange->podeSerImplementado())
                            @can('markAsImplemented', $logicChange)
                                <div class="text-center mt-3">
                                    <!-- Mobile: Link direto -->
                                    <a href="{{ route('logic-changes.mark-implemented-page', $logicChange) }}" 
                                       class="btn btn-primary btn-lg d-md-none">
                                        <i class="fas fa-check-double"></i> Marcar como Implementado
                                    </a>
                                    <!-- Desktop: Modal -->
                                    <button type="button" class="btn btn-primary btn-lg d-none d-md-inline-block" 
                                            data-bs-toggle="modal" data-bs-target="#implementedModal">
                                        <i class="fas fa-check-double"></i> Marcar como Implementado
                                    </button>
                                    <p class="text-muted mt-2">
                                        <small>Esta ação confirmará que a alteração foi implementada no sistema.<br>
                                        Disponível para: Admins, Técnicos de Automação, Eletricistas, Eletrotécnicos e Coordenadores de Manutenção.</small>
                                    </p>
                                </div>
                            @endcan
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

                    <div class="row">
                        <!-- Gerente de Manutenção -->
                        <div class="col-md-6 mb-3">
                            <div class="card {{ $logicChange->aprovacao_gerente_manutencao ? 'border-success' : 'border-warning' }}">
                                <div class="card-header {{ $logicChange->aprovacao_gerente_manutencao ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    <i class="fas fa-user-tie"></i> Gerente de Manutenção
                                </div>
                                <div class="card-body">
                                    @if($logicChange->aprovacao_gerente_manutencao)
                                        <p class="text-success mb-2">
                                            <i class="fas fa-check-circle"></i> <strong>Aprovado</strong>
                                        </p>
                                        <small class="text-muted">
                                            Aprovado em: {{ $logicChange->aprovacao_gerente_manutencao->format('d/m/Y H:i') }}
                                        </small>
                                        @if($logicChange->gerenteManutencao)
                                            <br><small class="text-muted">Por: {{ $logicChange->gerenteManutencao->name }}</small>
                                        @endif
                                        @if($logicChange->observacoes_gerente_manutencao)
                                            <div class="mt-2">
                                                <strong>Observações:</strong><br>
                                                <em>{{ $logicChange->observacoes_gerente_manutencao }}</em>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-warning mb-2">
                                            <i class="fas fa-clock"></i> <strong>Pendente</strong>
                                        </p>
                        @can('approveAsManager', $logicChange)
                            <!-- Mobile: Link direto -->
                            <a href="{{ route('logic-changes.approve-manager-page', $logicChange) }}" 
                               class="btn btn-success btn-sm d-md-none">
                                <i class="fas fa-check"></i> Aprovar
                            </a>
                            <!-- Desktop: Modal -->
                            <button type="button" class="btn btn-success btn-sm d-none d-md-inline-block" 
                                    data-bs-toggle="modal" data-bs-target="#approveManagerModal">
                                <i class="fas fa-check"></i> Aprovar
                            </button>
                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Coordenador de Manutenção -->
                        <div class="col-md-6 mb-3">
                            <div class="card {{ $logicChange->aprovacao_coordenador_manutencao ? 'border-success' : 'border-warning' }}">
                                <div class="card-header {{ $logicChange->aprovacao_coordenador_manutencao ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    <i class="fas fa-user-cog"></i> Coordenador de Manutenção
                                </div>
                                <div class="card-body">
                                    @if($logicChange->aprovacao_coordenador_manutencao)
                                        <p class="text-success mb-2">
                                            <i class="fas fa-check-circle"></i> <strong>Aprovado</strong>
                                        </p>
                                        <small class="text-muted">
                                            Aprovado em: {{ $logicChange->aprovacao_coordenador_manutencao->format('d/m/Y H:i') }}
                                        </small>
                                        @if($logicChange->coordenadorManutencao)
                                            <br><small class="text-muted">Por: {{ $logicChange->coordenadorManutencao->name }}</small>
                                        @endif
                                        @if($logicChange->observacoes_coordenador_manutencao)
                                            <div class="mt-2">
                                                <strong>Observações:</strong><br>
                                                <em>{{ $logicChange->observacoes_coordenador_manutencao }}</em>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-warning mb-2">
                                            <i class="fas fa-clock"></i> <strong>Pendente</strong>
                                        </p>
                        @can('approveAsCoordinator', $logicChange)
                            <!-- Mobile: Link direto -->
                            <a href="{{ route('logic-changes.approve-coordinator-page', $logicChange) }}" 
                               class="btn btn-success btn-sm d-md-none">
                                <i class="fas fa-check"></i> Aprovar
                            </a>
                            <!-- Desktop: Modal -->
                            <button type="button" class="btn btn-success btn-sm d-none d-md-inline-block" 
                                    data-bs-toggle="modal" data-bs-target="#approveCoordinatorModal">
                                <i class="fas fa-check"></i> Aprovar
                            </button>
                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>


                        <!-- Técnico Especialista -->
                        <div class="col-md-6 mb-3">
                            <div class="card {{ $logicChange->aprovacao_tecnico_especialista ? 'border-success' : 'border-warning' }}">
                                <div class="card-header {{ $logicChange->aprovacao_tecnico_especialista ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    <i class="fas fa-user-gear"></i> Técnico Especialista em Automação
                                </div>
                                <div class="card-body">
                                    @if($logicChange->aprovacao_tecnico_especialista)
                                        <p class="text-success mb-2">
                                            <i class="fas fa-check-circle"></i> <strong>Aprovado</strong>
                                        </p>
                                        <small class="text-muted">
                                            Aprovado em: {{ $logicChange->aprovacao_tecnico_especialista->format('d/m/Y H:i') }}
                                        </small>
                                        @if($logicChange->tecnicoEspecialista)
                                            <br><small class="text-muted">Por: {{ $logicChange->tecnicoEspecialista->name }}</small>
                                        @endif
                                        @if($logicChange->observacoes_tecnico_especialista)
                                            <div class="mt-2">
                                                <strong>Observações:</strong><br>
                                                <em>{{ $logicChange->observacoes_tecnico_especialista }}</em>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-warning mb-2">
                                            <i class="fas fa-clock"></i> <strong>Pendente</strong>
                                        </p>
                        @can('approveAsSpecialist', $logicChange)
                            <!-- Mobile: Link direto -->
                            <a href="{{ route('logic-changes.approve-specialist-page', $logicChange) }}" 
                               class="btn btn-success btn-sm d-md-none">
                                <i class="fas fa-check"></i> Aprovar
                            </a>
                            <!-- Desktop: Modal -->
                            <button type="button" class="btn btn-success btn-sm d-none d-md-inline-block" 
                                    data-bs-toggle="modal" data-bs-target="#approveSpecialistModal">
                                <i class="fas fa-check"></i> Aprovar
                            </button>
                        @endcan
                                    @endif
                                </div>
                            </div>
                </div>
            </div>
        </div>

                <!-- Seção de Implementação -->
                @if($logicChange->status === 'implementado')
                    <div class="mb-4">
                        <h5 class="text-success"><i class="fas fa-check-double"></i> Status de Implementação</h5>
                        <div class="alert alert-success">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-success">Implementado</span></p>
                                    <p class="mb-2"><strong>Data da Implementação:</strong> {{ $logicChange->data_implementacao?->format('d/m/Y H:i') }}</p>
                                    @if($logicChange->implementadoPor)
                                        <p class="mb-2"><strong>Implementado por:</strong> {{ $logicChange->implementadoPor->name }}</p>
                                    @endif
                                    <p class="mb-0"><strong>Registrado em:</strong> {{ $logicChange->implementado_em?->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($logicChange->observacoes_implementacao)
                                        <p class="mb-2"><strong>Observações da Implementação:</strong></p>
                                        <p class="mb-0 text-break">{{ $logicChange->observacoes_implementacao }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($logicChange->arquivos_anexos && count($logicChange->arquivos_anexos) > 0)
                    <div class="mb-4">
                        <h6><i class="fas fa-paperclip"></i> Arquivos Anexos</h6>
                        <div class="list-group">
                            @foreach($logicChange->arquivos_anexos as $index => $arquivo)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file"></i>
                                        <strong>{{ $arquivo['nome'] }}</strong>
                                        <small class="text-muted">({{ number_format($arquivo['size'] / 1024, 2) }} KB)</small>
                                    </div>
                                    <div>
                                        <a href="{{ asset('storage/' . $arquivo['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Baixar
                                        </a>
                                        @if($logicChange->podeSerEditado() && (auth()->user()->id == $logicChange->user_id || auth()->user()->isAdmin()))
                                            <form action="{{ route('logic-changes.remove-file', $logicChange) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="file_index" value="{{ $index }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Tem certeza que deseja remover este arquivo?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('logic-changes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <div>
                        @if($logicChange->podeSerEditado() && (auth()->user()->id == $logicChange->user_id || auth()->user()->isAdmin()))
                            <a href="{{ route('logic-changes.edit', $logicChange) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Informações da Solicitação -->
        <div class="card mb-4">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Informações</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Solicitante:</strong><br>
                    <i class="fas fa-user"></i> {{ $logicChange->user->name }}<br>
                    <small class="text-muted">{{ $logicChange->user->email }}</small>
                </div>

                @if($logicChange->tecnico)
                    <div class="mb-3">
                        <strong>Técnico Responsável:</strong><br>
                        <i class="fas fa-user-cog"></i> {{ $logicChange->tecnico->name }}<br>
                        <small class="text-muted">{{ $logicChange->tecnico->email }}</small>
                    </div>
                @endif

                @if($logicChange->unit)
                    <div class="mb-3">
                        <strong>Unidade:</strong><br>
                        <i class="fas fa-building"></i> {{ $logicChange->unit->name }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>


<!-- Modals de Aprovação -->

<!-- Modal Gerente de Manutenção -->
<div class="modal fade" id="approveManagerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprovação - Gerente de Manutenção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('logic-changes.approve-manager', $logicChange) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Você está aprovando como Gerente de Manutenção</strong><br>
                        Esta ação registrará sua aprovação com data e hora.
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes_gerente" class="form-label">Observações (opcional)</label>
                        <textarea class="form-control" id="observacoes_gerente" name="observacoes_gerente_manutencao" 
                                  rows="3" placeholder="Adicione observações sobre esta aprovação..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprovar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Coordenador de Manutenção -->
<div class="modal fade" id="approveCoordinatorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprovação - Coordenador de Manutenção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('logic-changes.approve-coordinator', $logicChange) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Você está aprovando como Coordenador de Manutenção</strong><br>
                        Esta ação registrará sua aprovação com data e hora.
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes_coordenador" class="form-label">Observações (opcional)</label>
                        <textarea class="form-control" id="observacoes_coordenador" name="observacoes_coordenador_manutencao" 
                                  rows="3" placeholder="Adicione observações sobre esta aprovação..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprovar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Técnico Especialista -->
<div class="modal fade" id="approveSpecialistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprovação - Técnico Especialista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('logic-changes.approve-specialist', $logicChange) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Você está aprovando como Técnico Especialista em Automação</strong><br>
                        Esta ação registrará sua aprovação técnica final com data e hora.
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes_especialista" class="form-label">Observações Técnicas (opcional)</label>
                        <textarea class="form-control" id="observacoes_especialista" name="observacoes_tecnico_especialista" 
                                  rows="3" placeholder="Adicione observações técnicas sobre esta aprovação..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprovar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Marcar como Implementado -->
<div class="modal fade" id="implementedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marcar como Implementado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('logic-changes.mark-implemented', $logicChange) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Confirmação de Implementação</strong><br>
                        Esta ação marcará a alteração como implementada no sistema.<br>
                        <small class="text-muted">Confirme que a implementação técnica foi realizada com sucesso.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Resumo da Alteração:</h6>
                        <p><strong>Título:</strong> {{ $logicChange->titulo }}</p>
                        <p><strong>Departamento:</strong> {{ $logicChange->departamento }}</p>
                        <p><strong>Solicitante:</strong> {{ $logicChange->solicitante }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes_implementacao" class="form-label">Observações da Implementação (opcional)</label>
                        <textarea class="form-control" id="observacoes_implementacao" name="observacoes_implementacao" 
                                  rows="3" placeholder="Adicione observações sobre a implementação realizada..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="data_implementacao" class="form-label">Data da Implementação</label>
                        <input type="datetime-local" class="form-control" id="data_implementacao" name="data_implementacao" 
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-double"></i> Confirmar Implementação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
