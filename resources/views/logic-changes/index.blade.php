@extends('layouts.app')

@section('title', 'Alterações de Lógica')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-cogs"></i> Alterações de Lógica</h2>
            <a href="{{ route('logic-changes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova Solicitação
            </a>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('logic-changes.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Título, solicitante, departamento...">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                            <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            <option value="em_execucao" {{ request('status') == 'em_execucao' ? 'selected' : '' }}>Em Execução</option>
                            <option value="implementado" {{ request('status') == 'implementado' ? 'selected' : '' }}>Implementado</option>
                            <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                            <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="departamento" class="form-label">Departamento</label>
                        <select class="form-select" id="departamento" name="departamento">
                            <option value="">Todos</option>
                            @foreach(\App\Models\LogicChange::DEPARTAMENTOS as $dept)
                                <option value="{{ $dept }}" {{ request('departamento') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('logic-changes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela -->
        <div class="card">
            <div class="card-body">
                @if($logicChanges->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Solicitante</th>
                                    <th>Departamento</th>
                                    <th>Status</th>
                                    <th>Criado por</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logicChanges as $logicChange)
                                    <tr>
                                        <td><strong>#{{ $logicChange->id }}</strong></td>
                                        <td>
                                            <a href="{{ route('logic-changes.show', $logicChange) }}" class="text-decoration-none">
                                                {{ $logicChange->titulo }}
                                            </a>
                                        </td>
                                        <td>{{ $logicChange->solicitante }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $logicChange->departamento }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $logicChange->getStatusClass() }}">
                                                {{ $logicChange->getStatusFormatado() }}
                                            </span>
                                        </td>
                                        <td>{{ $logicChange->user->name }}</td>
                                        <td>{{ $logicChange->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('logic-changes.show', $logicChange) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($logicChange->podeSerEditado() && (auth()->user()->id == $logicChange->user_id || auth()->user()->isAdmin()))
                                                    <a href="{{ route('logic-changes.edit', $logicChange) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $logicChanges->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma solicitação encontrada</h5>
                        <p class="text-muted">Clique em "Nova Solicitação" para criar a primeira solicitação de alteração de lógica.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
