@extends('layouts.app')

@section('title', 'Controle de Forcing - Dashboard')

@section('content')
<!-- CSS das melhorias -->
<link href="{{ asset('css/theme-improvements.css') }}" rel="stylesheet">

<div class="container-fluid fade-in-up">
    <!-- Header melhorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-glass border-glow rounded-3 p-4 shadow-glow">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <h1 class="h3 mb-0 text-glow">
                            <i class="fas fa-exclamation-triangle me-2"></i>Controle de Forcing
                        </h1>
                        <small class="text-white-50">Sistema de gerenciamento de forcing</small>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button id="refreshTableBtn" class="btn btn-primary btn-sm pulse-effect" title="Atualizar Lista">
                            <i class="fas fa-sync-alt" id="refreshIcon"></i> 
                            <span class="d-none d-sm-inline">Atualizar</span>
                        </button>
                        <a href="{{ route('forcing.terms') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Novo Forcing</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros melhorados -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-glass border-glow rounded-3 p-3">
                <form id="filtroForm" method="GET" action="{{ route('forcing.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label text-white">Status</label>
                            <select name="status" class="form-control">
                                <option value="todos">Todos</option>
                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="liberado" {{ request('status') == 'liberado' ? 'selected' : '' }}>Liberado</option>
                                <option value="forcado" {{ request('status') == 'forcado' ? 'selected' : '' }}>Forçado</option>
                                <option value="solicitacao_retirada" {{ request('status') == 'solicitacao_retirada' ? 'selected' : '' }}>Solicitação Retirada</option>
                                <option value="retirado" {{ request('status') == 'retirado' ? 'selected' : '' }}>Retirado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">Área</label>
                            <select name="area" class="form-control">
                                <option value="todas">Todas</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-white">Buscar</label>
                            <input type="text" name="busca" class="form-control" placeholder="Digite TAG ou descrição..." value="{{ request('busca') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-white">&nbsp;</label>
                            <a href="{{ route('forcing.index') }}" class="btn btn-warning w-100">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($forcings->count() > 0)
        <!-- Tabela melhorada -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-list me-2"></i>Lista de Forcings
                            <span class="badge badge-primary ms-2">{{ $forcings->count() }}</span>
                        </h5>
                        <small class="text-white-50">
                            Mostrando {{ $forcings->firstItem() }} a {{ $forcings->lastItem() }} de {{ $forcings->total() }}
                        </small>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TAG/Descrição</th>
                                    <th>Área</th>
                                    <th>Status</th>
                                    <th>Criado por</th>
                                    <th>Empresa/Setor</th>
                                    <th>Data do Forcing</th>
                                    <th>Liberador</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($forcings as $forcing)
                                <tr>
                                    <td class="fw-bold">{{ $forcing->id }}</td>
                                    <td>
                                        <a href="{{ route('forcing.show', $forcing) }}" class="text-decoration-none text-white">
                                            {{ $forcing->tag }}
                                        </a>
                                        <br>
                                        <small class="text-white-50">{{ $forcing->descricao_equipamento }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $forcing->area }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $forcing->status }}">{{ $forcing->status_texto }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-user-circle text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $forcing->user->name }}</div>
                                                <small class="text-white-50">{{ $forcing->user->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $forcing->user->empresa ?? 'N/A' }}</div>
                                            <small class="text-white-50">{{ $forcing->user->setor ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $forcing->data_forcing->format('d/m/Y') }}</div>
                                        <small class="text-white-50">{{ $forcing->data_forcing->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($forcing->liberador)
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-user-check text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $forcing->liberador->name }}</div>
                                                    <small class="text-white-50">{{ $forcing->liberador->username }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-white-50">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('forcing.show', $forcing) }}" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('update', $forcing)
                                                <a href="{{ route('forcing.edit', $forcing) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paginação melhorada -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-glass border-glow rounded-3 p-3">
                    {{ $forcings->links() }}
                </div>
            </div>
        </div>

        <!-- Cards de status melhorados -->
        <div class="row">
            <div class="col-md-2 mb-3">
                <div class="status-card card-pendente p-3 text-center" data-status="pendente" title="Clique para filtrar">
                    <div class="mb-2">
                        <i class="fas fa-clock fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['pendente'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Pendente</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="status-card card-liberado p-3 text-center" data-status="liberado" title="Clique para filtrar">
                    <div class="mb-2">
                        <i class="fas fa-check fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['liberado'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Liberado</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="status-card card-forcado p-3 text-center" data-status="forcado" title="Clique para filtrar">
                    <div class="mb-2">
                        <i class="fas fa-exclamation-triangle fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['forcado'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Forçado</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="status-card card-retirado p-3 text-center" data-status="solicitacao_retirada" title="Clique para filtrar">
                    <div class="mb-2">
                        <i class="fas fa-paper-plane fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['solicitacao_retirada'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Sol. Retirada</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="status-card card-retirado p-3 text-center" data-status="retirado" title="Clique para filtrar">
                    <div class="mb-2">
                        <i class="fas fa-check-double fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['retirado'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Retirado</p>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="status-card card-executado p-3 text-center" title="Forcing executados">
                    <div class="mb-2">
                        <i class="fas fa-tools fa-2x text-white"></i>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">{{ $contadoresGerais['executado'] ?? 'N/A' }}</h3>
                    <p class="mb-0 text-white-50">Executados</p>
                </div>
            </div>
        </div>
    @else
        <!-- Estado vazio melhorado -->
        <div class="row">
            <div class="col-12">
                <div class="bg-glass border-glow rounded-3 p-5 text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-white-50 mb-4"></i>
                    <h4 class="text-white mb-3">Nenhum forcing encontrado</h4>
                    <p class="text-white-50 mb-4">Seja o primeiro a criar um forcing no sistema!</p>
                    <a href="{{ route('forcing.terms') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Criar Primeiro Forcing
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// JavaScript melhorado com animações
document.addEventListener('DOMContentLoaded', function() {
    // Animar cards de status
    const statusCards = document.querySelectorAll('.status-card');
    statusCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in-up');
    });

    // Adicionar efeitos hover nos cards
    statusCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('glow-effect');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('glow-effect');
        });
    });

    // Filtros rápidos nos cards
    statusCards.forEach(card => {
        if (card.dataset.status) {
            card.addEventListener('click', function() {
                const status = this.dataset.status;
                const form = document.getElementById('filtroForm');
                const statusSelect = form.querySelector('select[name="status"]');
                statusSelect.value = status;
                form.submit();
            });
        }
    });
});
</script>
@endsection
