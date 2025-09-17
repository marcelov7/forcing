@extends('layouts.app')

@section('title', 'Marcar como Implementado')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-check-double"></i> Marcar como Implementado
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Informações da Solicitação -->
                    <div class="alert alert-info">
                        <h6><strong>Solicitação #{{ $logicChange->id }}</strong></h6>
                        <p class="mb-1"><strong>Título:</strong> {{ $logicChange->titulo }}</p>
                        <p class="mb-1"><strong>Solicitante:</strong> {{ $logicChange->solicitante }}</p>
                        <p class="mb-1"><strong>Departamento:</strong> {{ $logicChange->departamento }}</p>
                        <p class="mb-0"><strong>Data:</strong> {{ $logicChange->data_solicitacao_form->format('d/m/Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Descrição da Alteração:</h6>
                        <p class="text-break">{{ $logicChange->descricao_alteracao }}</p>
                    </div>

                    <div class="mb-4">
                        <h6>Motivo da Alteração:</h6>
                        <p class="text-break">{{ $logicChange->motivo_alteracao }}</p>
                    </div>

                    <!-- Status das Aprovações -->
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle"></i> Status das Aprovações</h6>
                        <p class="mb-2">Progresso: {{ $logicChange->getPorcentagemAprovacoes() }}% Completo</p>
                        @if($logicChange->todasAprovacoesCompletas())
                            <p class="mb-0"><strong>✅ Todas as aprovações foram concedidas!</strong></p>
                        @else
                            <p class="mb-0"><strong>⏳ Aprovações pendentes:</strong> {{ implode(', ', $logicChange->getAprovadoresPendentes()) }}</p>
                        @endif
                    </div>

                    <!-- Formulário de Implementação -->
                    <div class="alert alert-warning">
                        <strong>Confirmação de Implementação</strong><br>
                        Esta ação marcará a alteração como implementada no sistema.<br>
                        <small class="text-muted">Confirme que a implementação técnica foi realizada com sucesso.</small>
                    </div>

                    <form action="{{ route('logic-changes.mark-implemented', $logicChange) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="data_implementacao" class="form-label">Data da Implementação</label>
                            <input type="datetime-local" class="form-control" id="data_implementacao" name="data_implementacao" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observacoes_implementacao" class="form-label">Observações da Implementação (opcional)</label>
                            <textarea class="form-control" id="observacoes_implementacao" name="observacoes_implementacao" 
                                      rows="4" placeholder="Adicione observações sobre a implementação realizada..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-double"></i> Confirmar Implementação
                            </button>
                            <a href="{{ route('logic-changes.show', $logicChange) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
