@extends('layouts.app')

@section('title', 'Lista de Forcing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-exclamation-triangle"></i> Controle de Forcing</h1>
    <div class="d-flex gap-2">
        <button id="refreshTableBtn" class="btn btn-outline-primary btn-sm" title="Atualizar Lista">
            <i class="fas fa-sync-alt" id="refreshIcon"></i> 
            <span class="d-none d-md-inline">Atualizar</span>
        </button>
        <a href="{{ route('forcing.terms') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Forcing
        </a>
    </div>
</div>

<!-- Banner de Notificação para Solicitações de Retirada -->
@php
    $solicitacoesRetirada = $forcings->where('status', 'solicitacao_retirada');
@endphp

@if($solicitacoesRetirada->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-bell fa-2x me-3"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">
                    <i class="fas fa-exclamation-triangle"></i> 
                    {{ $solicitacoesRetirada->count() }} Solicitação(ões) de Retirada Pendente(s)
                </h5>
                @foreach($solicitacoesRetirada as $forcing)
                    <div class="border-start border-warning border-3 ps-3 mb-2">
                        <strong>TAG:</strong> <code class="text-primary">{{ $forcing->tag }}</code> |
                        <strong>Equipamento:</strong> {{ Str::limit($forcing->descricao_equipamento, 40) }} |
                        <strong>Área:</strong> <span class="badge bg-secondary">{{ $forcing->area }}</span>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-user"></i> 
                            Solicitado por: <strong>{{ $forcing->solicitadoRetiradaPor->name ?? 'N/A' }}</strong> 
                            em {{ $forcing->data_solicitacao_retirada->format('d/m/Y H:i') }}
                        </small>
                    </div>
                @endforeach
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Executantes: Verifiquem os detalhes antes de proceder com a retirada.
                </small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Painel de Filtros -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-filter"></i> Filtros</h6>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
                <i class="fas fa-chevron-down"></i> <span class="d-none d-sm-inline">Expandir</span>
            </button>
        </div>
    </div>
    <div class="collapse" id="filtrosCollapse">
        <div class="card-body">
            <form method="GET" action="{{ route('forcing.index') }}" id="filtroForm">
                <!-- Filtros Principais - Sempre Visíveis -->
                <div class="row g-2 mb-3">
                    <!-- Busca por TAG/Descrição -->
                    <div class="col-12 col-md-6">
                        <label class="form-label small">🔍 Buscar TAG/Equipamento</label>
                        <input type="text" class="form-control" name="busca" 
                               value="{{ request('busca') }}" 
                               placeholder="Digite TAG ou descrição...">
                    </div>

                    <!-- Filtro por Status -->
                    <div class="col-6 col-md-3">
                        <label class="form-label small">📊 Status</label>
                        <select class="form-select" name="status">
                            <option value="todos" {{ request('status') == 'todos' ? 'selected' : '' }}>Todos</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>⏳ Pendente</option>
                            <option value="liberado" {{ request('status') == 'liberado' ? 'selected' : '' }}>✅ Liberado</option>
                            <option value="forcado" {{ request('status') == 'forcado' ? 'selected' : '' }}>⚠️ Forçado</option>
                            <option value="solicitacao_retirada" {{ request('status') == 'solicitacao_retirada' ? 'selected' : '' }}>✈️ Sol. Retirada</option>
                            <option value="retirado" {{ request('status') == 'retirado' ? 'selected' : '' }}>✅✅ Retirado</option>
                        </select>
                    </div>

                    <!-- Filtro por Área -->
                    <div class="col-6 col-md-3">
                        <label class="form-label small">🏭 Área</label>
                        <select class="form-select" name="area">
                            <option value="todas" {{ request('area') == 'todas' ? 'selected' : '' }}>Todas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                                    {{ $area }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filtros Avançados - Collapsible em Mobile -->
                <div class="d-md-block">
                    <div class="d-md-none mb-2">
                        <button class="btn btn-sm btn-outline-info w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosAvancados">
                            <i class="fas fa-cogs"></i> Filtros Avançados
                        </button>
                    </div>
                    
                    <div class="collapse d-md-block" id="filtrosAvancados">
                        <div class="row g-2 mb-3">
                            <!-- Filtro por Situação -->
                            <div class="col-6 col-md-3">
                                <label class="form-label small">⚙️ Situação</label>
                                <select class="form-select" name="situacao">
                                    <option value="todas" {{ request('situacao') == 'todas' ? 'selected' : '' }}>Todas</option>
                                    <option value="ativado" {{ request('situacao') == 'ativado' ? 'selected' : '' }}>🟢 Ativado</option>
                                    <option value="desativado" {{ request('situacao') == 'desativado' ? 'selected' : '' }}>🔴 Desativado</option>
                                    <option value="ativacao_futura" {{ request('situacao') == 'ativacao_futura' ? 'selected' : '' }}>🟡 Ativação Futura</option>
                                </select>
                            </div>

                            <!-- Filtro por Criador -->
                            <div class="col-6 col-md-3">
                                <label class="form-label small">👤 Criado por</label>
                                <select class="form-select" name="criador">
                                    <option value="todos" {{ request('criador') == 'todos' ? 'selected' : '' }}>Todos</option>
                                    @foreach($criadores as $criador)
                                        <option value="{{ $criador->id }}" {{ request('criador') == $criador->id ? 'selected' : '' }}>
                                            {{ $criador->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtros de Data -->
                            <div class="col-6 col-md-3">
                                <label class="form-label small">📅 Data Início</label>
                                <input type="date" class="form-control" name="data_inicio" value="{{ request('data_inicio') }}">
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="form-label small">📅 Data Fim</label>
                                <input type="date" class="form-control" name="data_fim" value="{{ request('data_fim') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="row g-2">
                    <!-- Botões Principais -->
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Filtrar</span>
                            </button>
                            <a href="{{ route('forcing.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Limpar</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Botões de Filtros Rápidos -->
                <div class="row g-2 mt-2">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-1 justify-content-start">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="aplicarFiltrosRapidos('forcado')">
                                ⚠️ <span class="d-none d-sm-inline">Ativos</span>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="aplicarFiltrosRapidos('retirado')">
                                ✅ <span class="d-none d-sm-inline">Concluídos</span>
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="aplicarFiltrosRapidos('solicitacao_retirada')">
                                🔔 <span class="d-none d-sm-inline">Pendente Retirada</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Resumo dos Resultados -->
@if(request()->hasAny(['status', 'area', 'situacao', 'criador', 'data_inicio', 'data_fim', 'busca']))
    <div class="alert alert-info mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-info-circle"></i> 
                <strong>{{ $forcings->count() }}</strong> forcing(s) encontrado(s) 
                @if(request('busca'))
                    para "<strong>{{ request('busca') }}</strong>"
                @endif
                @if(request('status') && request('status') !== 'todos')
                    • Status: <span class="badge bg-primary">{{ ucfirst(request('status')) }}</span>
                @endif
                @if(request('area') && request('area') !== 'todas')
                    • Área: <span class="badge bg-secondary">{{ request('area') }}</span>
                @endif
            </div>
            <small class="text-muted">Total no sistema: {{ \App\Models\Forcing::count() }}</small>
        </div>
    </div>
@endif

@if($forcings->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body p-0">
                    <div id="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>TAG/Descrição</th>
                                    <th>Área</th>
                                    <th>Status</th>
                                    <th>Criado por</th>
                                    <th>Empresa/Setor</th>
                                    <th>Data do Forcing</th>
                                    <th>Liberador</th>
                                    <th>Data Liberação/Retirada</th>
                                    <th>Executante</th>
                                    <th>Local Execução</th>
                                    <th>Status Execução</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($forcings as $forcing)
                                    <tr>
                                        <td>{{ $forcing->id }}</td>
                                        <td>
                                            <code class="text-primary">{{ $forcing->tag }}</code>
                                            <br><small class="text-muted">{{ Str::limit($forcing->descricao_equipamento, 50) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $forcing->area }}</span>
                                        </td>
                                        <td>
                                            @if($forcing->status === 'pendente')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock"></i> Pendente
                                                </span>
                                            @elseif($forcing->status === 'liberado')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Liberado
                                                </span>
                                            @elseif($forcing->status === 'forcado')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-exclamation-triangle"></i> Forçado
                                                </span>
                                            @elseif($forcing->status === 'solicitacao_retirada')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-paper-plane"></i> Sol. Retirada
                                                </span>
                                            @else
                                                <span class="badge bg-dark">
                                                    <i class="fas fa-check-double"></i> Retirado
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $forcing->user->name }}</strong>
                                            <br><small class="text-muted">{{ $forcing->user->username }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $forcing->user->empresa }}</strong>
                                            <br><small class="text-muted">{{ $forcing->user->setor }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $forcing->data_forcing->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($forcing->liberador)
                                                <strong>{{ $forcing->liberador->name }}</strong>
                                                <br><small class="text-muted">{{ $forcing->liberador->username }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($forcing->status === 'liberado' && $forcing->data_liberacao)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle"></i> 
                                                    {{ $forcing->data_liberacao->format('d/m/Y H:i') }}
                                                </small>
                                                <br><small class="text-muted">Liberado</small>
                                            @elseif($forcing->status === 'retirado' && $forcing->data_retirada)
                                                <small class="text-dark">
                                                    <i class="fas fa-check-double"></i> 
                                                    {{ $forcing->data_retirada->format('d/m/Y H:i') }}
                                                </small>
                                                <br><small class="text-muted">Retirado</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($forcing->executante)
                                                <strong>{{ $forcing->executante->name }}</strong>
                                                <br><small class="text-muted">{{ $forcing->executante->username }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($forcing->local_execucao)
                                                <span class="badge bg-info">{{ $forcing->getLocalExecucaoTexto() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($forcing->status_execucao === 'executado')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Executado
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i> Pendente
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('forcing.show', $forcing) }}" class="btn btn-sm btn-outline-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if(auth()->user()->perfil === 'admin' || $forcing->user_id === auth()->id())
                                                    <a href="{{ route('forcing.edit', $forcing) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                
                                                @if((auth()->user()->perfil === 'liberador' && $forcing->liberador_id === auth()->id()) || auth()->user()->perfil === 'admin')
                                                    @if($forcing->status === 'pendente')
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                data-bs-toggle="modal" data-bs-target="#liberarModal{{ $forcing->id }}" title="Liberar">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                @endif

                                                @if(auth()->user()->perfil === 'executante' && $forcing->status_execucao === 'pendente' && $forcing->status === 'liberado')
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#execucaoModal{{ $forcing->id }}" title="Registrar Execução">
                                                        <i class="fas fa-tools"></i>
                                                    </button>
                                                @elseif(auth()->user()->perfil === 'admin' && $forcing->status_execucao === 'pendente' && $forcing->status === 'liberado')
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#execucaoModal{{ $forcing->id }}" title="Registrar Execução">
                                                        <i class="fas fa-tools"></i>
                                                    </button>
                                                @endif

                                                @if($forcing->status === 'forcado')
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" data-bs-target="#solicitarRetiradaModal{{ $forcing->id }}" title="Solicitar Retirada">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                @endif

                                                @if((auth()->user()->perfil === 'executante' || auth()->user()->perfil === 'admin') && $forcing->status === 'solicitacao_retirada')
                                                    <button type="button" class="btn btn-sm btn-outline-dark" 
                                                            data-bs-toggle="modal" data-bs-target="#retirarModal{{ $forcing->id }}" title="Retirar Forcing">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(auth()->user()->perfil === 'admin')
                                                    <form action="{{ route('forcing.destroy', $forcing) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Tem certeza que deseja excluir?')" title="Excluir">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal para liberar forcing -->
                                    @if((auth()->user()->perfil === 'liberador' || auth()->user()->perfil === 'admin') && $forcing->status === 'pendente')
                                        <div class="modal fade" id="liberarModal{{ $forcing->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('forcing.liberar', $forcing) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Liberar Forcing</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Forcing:</strong> {{ $forcing->titulo }}</p>
                                                            <div class="mb-3">
                                                                <label for="observacoes{{ $forcing->id }}" class="form-label">Observações</label>
                                                                <textarea class="form-control" id="observacoes{{ $forcing->id }}" 
                                                                          name="observacoes" rows="3" placeholder="Observações sobre a liberação (opcional)"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-check"></i> Liberar Forcing
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Modal para registrar execução -->
                                    @if((auth()->user()->perfil === 'executante' || auth()->user()->perfil === 'admin') && $forcing->status_execucao === 'pendente')
                                        <div class="modal fade" id="execucaoModal{{ $forcing->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('forcing.registrar-execucao', $forcing) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Registrar Execução</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Forcing:</strong> {{ $forcing->titulo }}</p>
                                                            
                                                            <div class="mb-3">
                                                                <label for="local_execucao{{ $forcing->id }}" class="form-label">Local de Execução <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="local_execucao{{ $forcing->id }}" name="local_execucao" required>
                                                                    <option value="">Selecione o local...</option>
                                                                    <option value="supervisorio">Supervisório</option>
                                                                    <option value="plc">PLC</option>
                                                                    <option value="local">Local</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="observacoes_execucao{{ $forcing->id }}" class="form-label">Observações da Execução</label>
                                                                <textarea class="form-control" id="observacoes_execucao{{ $forcing->id }}" 
                                                                          name="observacoes_execucao" rows="3" placeholder="Observações sobre a execução (opcional)"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-tools"></i> Registrar Execução
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div id="pagination-container">
        @if($forcings->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $forcings->firstItem() }} a {{ $forcings->lastItem() }} de {{ $forcings->total() }} forcings
                </div>
                <div>
                    {{ $forcings->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modais para ações dos forcings -->
    <div data-modals-container>
        @foreach($forcings as $forcing)
            <!-- Modal para solicitar retirada -->
            @if($forcing->status === 'forcado')
                <div class="modal fade" id="solicitarRetiradaModal{{ $forcing->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('forcing.solicitar-retirada', $forcing) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Solicitar Retirada</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Forcing:</strong> {{ $forcing->titulo ?? $forcing->tag }}</p>
                                    <p class="text-muted">Você está solicitando a retirada deste forcing. O executante será notificado.</p>
                                    
                                    <div class="mb-3">
                                        <label for="descricao_resolucao{{ $forcing->id }}" class="form-label">Descrição da Resolução <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="descricao_resolucao{{ $forcing->id }}" 
                                                  name="descricao_resolucao" rows="4" required 
                                                  placeholder="Descreva como foi resolvido o problema que ocasionou o forcing..."></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="observacoes{{ $forcing->id }}" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes{{ $forcing->id }}" 
                                                  name="observacoes" rows="3" 
                                                  placeholder="Observações adicionais (opcional)"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-paper-plane"></i> Solicitar Retirada
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal para retirar forcing definitivamente -->
            @if((auth()->user()->perfil === 'executante' || auth()->user()->perfil === 'admin') && $forcing->status === 'solicitacao_retirada')
                <div class="modal fade" id="retirarModal{{ $forcing->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('forcing.retirar', $forcing) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Retirar Forcing</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Forcing:</strong> {{ $forcing->titulo }}</p>
                                    <p class="text-muted">Esta ação finalizará o ciclo do forcing, marcando-o como retirado definitivamente.</p>
                                    <div class="mb-3">
                                        <label for="observacoes_retirada{{ $forcing->id }}" class="form-label">Observações</label>
                                        <textarea class="form-control" id="observacoes_retirada{{ $forcing->id }}" 
                                                  name="observacoes" rows="3" placeholder="Observações sobre a retirada (opcional)"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fas fa-check-double"></i> Retirar Forcing
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Resumo dos status -->
    <div class="row mt-4">
        <div class="col-md-2">
            <div class="card bg-secondary text-white" data-status="pendente" title="Clique para filtrar">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status', 'pendente')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-clock"></i> Pendente</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white" data-status="liberado" title="Clique para filtrar">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status', 'liberado')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-check"></i> Liberado</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white" data-status="forcado" title="Clique para filtrar">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status', 'forcado')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-exclamation-triangle"></i> Forçado</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white" data-status="solicitacao_retirada" title="Clique para filtrar">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status', 'solicitacao_retirada')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-paper-plane"></i> Sol. Retirada</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white" data-status="retirado" title="Clique para filtrar">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status', 'retirado')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-check-double"></i> Retirado</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-primary text-white" title="Forcing executados">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $forcings->where('status_execucao', 'executado')->count() }}</h3>
                    <p class="mb-0"><i class="fas fa-tools"></i> Executados</p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                    <h4>Nenhum forcing encontrado</h4>
                    <p class="text-muted">Seja o primeiro a criar um forcing no sistema!</p>
                    <a href="{{ route('forcing.terms') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Primeiro Forcing
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
// Função para aplicar filtros rápidos
function aplicarFiltrosRapidos(status) {
    const form = document.getElementById('filtroForm');
    const statusSelect = form.querySelector('select[name="status"]');
    statusSelect.value = status;
    form.submit();
}

// Auto-expandir filtros se houver parâmetros de filtro na URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const temFiltros = Array.from(urlParams.keys()).some(key => 
        ['status', 'area', 'situacao', 'criador', 'data_inicio', 'data_fim', 'busca'].includes(key)
    );
    
    if (temFiltros) {
        const collapse = new bootstrap.Collapse(document.getElementById('filtrosCollapse'), {
            show: true
        });
        
        // Auto-expandir filtros avançados em mobile se houver filtros aplicados
        if (window.innerWidth < 768) {
            const filtrosAvancados = document.getElementById('filtrosAvancados');
            if (filtrosAvancados) {
                const collapseAvancados = new bootstrap.Collapse(filtrosAvancados, {
                    show: true
                });
            }
        }
    }
});

// Função para aplicar filtro por busca em tempo real (opcional)
function aplicarBuscaRapida() {
    const busca = document.querySelector('input[name="busca"]').value;
    if (busca.length >= 2 || busca.length === 0) {
        document.getElementById('filtroForm').submit();
    }
}

// Cards de estatísticas clicáveis para filtros rápidos
document.querySelectorAll('.card[data-status]').forEach(card => {
    card.style.cursor = 'pointer';
    card.addEventListener('click', function() {
        const status = this.getAttribute('data-status');
        aplicarFiltrosRapidos(status);
    });
});

// Melhorar experiência mobile - ajustar altura dos selects
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        // Adicionar classe para melhor visualização em mobile
        document.querySelectorAll('.form-select, .form-control').forEach(element => {
            element.style.fontSize = '16px'; // Evita zoom no iOS
        });
    }
});
</script>

<style>
/* Estilos customizados para melhor responsividade móvel */
@media (max-width: 767.98px) {
    /* Reduzir espaçamento em mobile */
    .card-body {
        padding: 1rem 0.75rem;
    }
    
    /* Melhor aparência dos botões pequenos em mobile */
    .btn-sm {
        font-size: 0.775rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Ajustar altura dos campos de formulário */
    .form-control, .form-select {
        font-size: 16px !important; /* Evita zoom automático no iOS */
        height: auto;
        min-height: 38px;
    }
    
    /* Melhor espaçamento para labels */
    .form-label.small {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    /* Botões de filtro rápido mais compactos */
    .btn-outline-info, .btn-outline-success, .btn-outline-warning {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Melhor alinhamento dos gaps */
    .gap-1 > * + * {
        margin-left: 0.25rem !important;
    }
    
    .gap-2 > * + * {
        margin-left: 0.5rem !important;
    }
}

/* Estilos para tablets */
@media (min-width: 768px) and (max-width: 991.98px) {
    .btn-sm span.d-none {
        display: inline !important;
    }
}

/* Animação suave para colapsos */
.collapsing {
    transition: height 0.2s ease;
}

/* Hover states para cards clicáveis */
.card[data-status]:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

/* Melhor indicador visual para filtros ativos */
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Badge customizado para contadores */
.badge.bg-primary, .badge.bg-secondary {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}
</style>

@endsection

@section('scripts')
<script>
// Função para filtros rápidos
function aplicarFiltrosRapidos(status) {
    document.querySelector('select[name="status"]').value = status;
    document.getElementById('filtroForm').submit();
}

// AJAX Refresh da Tabela
function refreshTable() {
    const btn = document.getElementById('refreshTableBtn');
    const icon = document.getElementById('refreshIcon');
    const tableContainer = document.getElementById('table-container');
    const paginationContainer = document.getElementById('pagination-container');
    
    // Desabilitar botão e mostrar loading
    btn.disabled = true;
    icon.classList.add('fa-spin');
    
    // Adicionar efeito visual à tabela
    if (tableContainer) {
        tableContainer.style.opacity = '0.6';
    }
    
    // Preparar dados do formulário de filtros
    const formData = new FormData(document.getElementById('filtroForm'));
    const params = new URLSearchParams(formData);
    
    // Fazer requisição AJAX
    fetch(`{{ route('forcing.refresh-table') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: params
    })
    .then(response => {
        console.log('Status da resposta:', response.status);
        console.log('Headers da resposta:', response.headers.get('content-type'));
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.text();
    })
    .then(text => {
        console.log('Resposta recebida:', text.substring(0, 200) + '...');
        
        try {
            const data = JSON.parse(text);
            
            if (data.success) {
                // Atualizar tabela
                if (tableContainer && data.html) {
                    tableContainer.innerHTML = data.html;
                }
                
                // Atualizar paginação
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                // Atualizar modais
                const modalsContainer = document.querySelector('[data-modals-container]');
                if (modalsContainer && data.modals) {
                    modalsContainer.innerHTML = data.modals;
                }
                
                // Reativar modais do Bootstrap
                if (typeof bootstrap !== 'undefined') {
                    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(element => {
                        const targetModal = document.querySelector(element.getAttribute('data-bs-target'));
                        if (targetModal) {
                            new bootstrap.Modal(targetModal);
                        }
                    });
                }
                
                // Mostrar notificação de sucesso
                showUpdateNotification(data.total, data.timestamp);
            } else {
                throw new Error(data.message || 'Erro desconhecido na resposta');
            }
        } catch (parseError) {
            console.error('Erro ao fazer parse do JSON:', parseError);
            console.error('Resposta completa:', text);
            throw new Error('Resposta do servidor não é um JSON válido. Verifique o console para detalhes.');
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar tabela:', error);
        showErrorNotification('Erro ao atualizar a lista: ' + error.message);
    })
    .finally(() => {
        // Restaurar botão e tabela
        btn.disabled = false;
        icon.classList.remove('fa-spin');
        
        if (tableContainer) {
            tableContainer.style.opacity = '1';
        }
    });
}

// Adicionar evento ao botão de refresh
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refreshTableBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', refreshTable);
    }
});

// Função para mostrar notificação de sucesso
function showUpdateNotification(total, timestamp) {
    // Remover notificação anterior se existir
    const existingToast = document.getElementById('updateToast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Criar nova notificação
    const toastHTML = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
            <div id="updateToast" class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Lista Atualizada</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <strong>Lista atualizada com sucesso!</strong><br>
                    <small class="text-muted">
                        <i class="fas fa-list"></i> Total: ${total} registros<br>
                        <i class="fas fa-clock"></i> ${timestamp}
                    </small>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    
    // Auto-remover após 3 segundos
    setTimeout(() => {
        const toast = document.getElementById('updateToast');
        if (toast) {
            toast.remove();
        }
    }, 3000);
}

// Função para mostrar notificação de erro
function showErrorNotification(message) {
    // Remover notificação anterior se existir
    const existingToast = document.getElementById('errorToast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Criar nova notificação de erro
    const toastHTML = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
            <div id="errorToast" class="toast show" role="alert">
                <div class="toast-header bg-danger text-white">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong class="me-auto">Erro</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        const toast = document.getElementById('errorToast');
        if (toast) {
            toast.remove();
        }
    }, 5000);
}

// Opcional: Auto-refresh a cada 30 segundos (descomente se necessário)
// setInterval(refreshTable, 30000);
</script>
@endsection
