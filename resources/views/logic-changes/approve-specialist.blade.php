@extends('layouts.app')

@section('title', 'Aprovação - Técnico Especialista')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-gear"></i> Aprovação - Técnico Especialista em Automação
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

                    <!-- Formulário de Aprovação -->
                    <div class="alert alert-warning">
                        <strong>Você está aprovando como Técnico Especialista em Automação</strong><br>
                        Esta ação registrará sua aprovação técnica final com data e hora.
                    </div>

                    <form action="{{ route('logic-changes.approve-specialist', $logicChange) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="observacoes_tecnico_especialista" class="form-label">Observações Técnicas (opcional)</label>
                            <textarea class="form-control" id="observacoes_tecnico_especialista" name="observacoes_tecnico_especialista" 
                                      rows="4" placeholder="Adicione observações técnicas sobre esta aprovação..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Aprovar como Técnico Especialista
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
