@extends('layouts.app')

@section('title', 'Solicitar Retirada')

@section('content')
<div class="container-fluid px-3">
    <!-- Header Mobile -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('forcing.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="h4 mb-0">
                <i class="fas fa-paper-plane text-info me-2"></i>
                Solicitar Retirada
            </h1>
            <small class="text-muted">Solicite a retirada do forcing</small>
        </div>
    </div>

    <!-- Informações do Forcing -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Informações do Forcing
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">TAG:</label>
                    <p class="form-control-plaintext bg-light p-2 rounded">{{ $forcing->tag }}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Área:</label>
                    <p class="form-control-plaintext bg-light p-2 rounded">{{ $forcing->area }}</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Status:</label>
                    <span class="badge bg-warning fs-6">{{ ucfirst($forcing->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Aviso -->
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Atenção:</strong> Você está solicitando a retirada deste forcing. O executante será notificado e poderá retirar o forcing após confirmar a resolução do problema.
    </div>

    <!-- Formulário de Solicitação -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>
                Dados da Solicitação
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('forcing.solicitar-retirada', $forcing) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="descricao_resolucao" class="form-label">
                        <i class="fas fa-clipboard-check me-1"></i>
                        Descrição da Resolução <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control form-control-lg" id="descricao_resolucao" 
                              name="descricao_resolucao" rows="6" required 
                              placeholder="Descreva detalhadamente como foi resolvido o problema que ocasionou o forcing..."></textarea>
                    <div class="form-text">Explique passo a passo como o problema foi solucionado</div>
                </div>

                <div class="mb-4">
                    <label for="observacoes" class="form-label">
                        <i class="fas fa-comment-alt me-1"></i>
                        Observações Adicionais
                    </label>
                    <textarea class="form-control form-control-lg" id="observacoes" 
                              name="observacoes" rows="4" 
                              placeholder="Observações adicionais sobre a solicitação de retirada (opcional)"></textarea>
                    <div class="form-text">Informações complementares sobre a solicitação</div>
                </div>

                <!-- Botões de Ação -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-info btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>
                        Solicitar Retirada
                    </button>
                    <a href="{{ route('forcing.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="alert alert-light mt-4">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>
            Como funciona a retirada?
        </h6>
        <ul class="mb-0">
            <li>Você solicita a retirada descrevendo como o problema foi resolvido</li>
            <li>O executante recebe uma notificação</li>
            <li>O executante confirma a retirada após verificar a resolução</li>
            <li>O forcing é marcado como retirado</li>
        </ul>
    </div>
</div>

<style>
/* Estilos específicos para mobile */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    
    .card {
        border-radius: 1rem !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-header {
        border-radius: 1rem 1rem 0 0 !important;
        padding: 1rem !important;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 1rem 1.5rem !important;
        font-size: 1.1rem !important;
        border-radius: 0.75rem !important;
    }
    
    .form-control-lg {
        font-size: 16px !important; /* Previne zoom no iOS */
        padding: 1rem !important;
        border-radius: 0.75rem !important;
        border: 2px solid #dee2e6 !important;
    }
    
    .form-control-lg:focus {
        border-color: #0dcaf0 !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25) !important;
    }
    
    .form-label {
        font-size: 1rem !important;
        font-weight: 600 !important;
        margin-bottom: 0.75rem !important;
    }
    
    .form-text {
        font-size: 0.9rem !important;
        margin-top: 0.5rem !important;
    }
    
    .badge {
        font-size: 0.9rem !important;
        padding: 0.5rem 1rem !important;
    }
    
    .alert {
        border-radius: 0.75rem !important;
        padding: 1rem !important;
    }
    
    .alert-heading {
        font-size: 1rem !important;
        font-weight: 600 !important;
    }
    
    .alert ul {
        padding-left: 1.5rem !important;
    }
    
    .alert li {
        margin-bottom: 0.5rem !important;
    }
}
</style>
@endsection
