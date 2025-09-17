@extends('layouts.app')

@section('title', 'Editar Forcing')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Editar Forcing</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('forcing.update', $forcing) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo', $forcing->titulo) }}" required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="4" 
                                  placeholder="Descreva detalhadamente o forcing...">{{ old('descricao', $forcing->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campos do equipamento -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="situacao_equipamento" class="form-label">Situação do Equipamento <span class="text-danger">*</span></label>
                                <select class="form-select @error('situacao_equipamento') is-invalid @enderror" 
                                        id="situacao_equipamento" name="situacao_equipamento" required>
                                    <option value="">Selecione a situação...</option>
                                    <option value="desativado" {{ old('situacao_equipamento', $forcing->situacao_equipamento) == 'desativado' ? 'selected' : '' }}>Desativado</option>
                                    <option value="ativacao_futura" {{ old('situacao_equipamento', $forcing->situacao_equipamento) == 'ativacao_futura' ? 'selected' : '' }}>Ativação Futura</option>
                                    <option value="em_atividade" {{ old('situacao_equipamento', $forcing->situacao_equipamento) == 'em_atividade' ? 'selected' : '' }}>Em Atividade</option>
                                </select>
                                @error('situacao_equipamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tag" class="form-label">TAG <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tag') is-invalid @enderror" 
                                       id="tag" name="tag" value="{{ old('tag', $forcing->tag) }}" required 
                                       placeholder="Ex: TAG-001, PUMP-01...">
                                @error('tag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao_equipamento" class="form-label">Descrição do Equipamento <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descricao_equipamento') is-invalid @enderror" 
                                  id="descricao_equipamento" name="descricao_equipamento" rows="3" required 
                                  placeholder="Descreva detalhadamente o equipamento...">{{ old('descricao_equipamento', $forcing->descricao_equipamento) }}</textarea>
                        @error('descricao_equipamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('area') is-invalid @enderror" 
                               id="area" name="area" value="{{ old('area', $forcing->area) }}" required 
                               placeholder="Ex: Produção, Manutenção, Utilidades...">
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-info-circle"></i> Informações do Forcing</h6>
                                    <p class="mb-1"><strong>Criado em:</strong> {{ $forcing->data_forcing->format('d/m/Y H:i') }}</p>
                                    <p class="mb-1"><strong>Status atual:</strong> 
                                        @if($forcing->status === 'forcado')
                                            <span class="badge bg-danger">Forçado</span>
                                        @else
                                            <span class="badge bg-success">Retirado</span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Status execução:</strong> 
                                        @if($forcing->status_execucao === 'executado')
                                            <span class="badge bg-success">Executado</span>
                                        @else
                                            <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </p>
                                    @if($forcing->local_execucao)
                                        <p class="mb-0"><strong>Local execução:</strong> 
                                            <span class="badge bg-info">{{ $forcing->getLocalExecucaoTexto() }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-user"></i> Informações do Solicitante</h6>
                                    <p class="mb-1"><strong>Nome:</strong> {{ $forcing->user->name }}</p>
                                    <p class="mb-1"><strong>Empresa:</strong> {{ $forcing->user->empresa }}</p>
                                    <p class="mb-1"><strong>Setor:</strong> {{ $forcing->user->setor }}</p>
                                    <p class="mb-0"><strong>E-mail:</strong> {{ $forcing->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($forcing->liberador)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fas fa-check-circle"></i> Informações de Liberação</h6>
                                        <p class="mb-1"><strong>Liberado por:</strong> {{ $forcing->liberador->name }}</p>
                                        @if($forcing->data_retirada)
                                            <p class="mb-1"><strong>Data retirada:</strong> {{ $forcing->data_retirada->format('d/m/Y H:i') }}</p>
                                        @endif
                                        @if($forcing->observacoes)
                                            <p class="mb-0"><strong>Observações:</strong> {{ $forcing->observacoes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($forcing->executante)
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6><i class="fas fa-tools"></i> Informações de Execução</h6>
                                            <p class="mb-1"><strong>Executado por:</strong> {{ $forcing->executante->name }}</p>
                                            @if($forcing->data_execucao)
                                                <p class="mb-1"><strong>Data execução:</strong> {{ $forcing->data_execucao->format('d/m/Y H:i') }}</p>
                                            @endif
                                            @if($forcing->local_execucao)
                                                <p class="mb-1"><strong>Local:</strong> {{ $forcing->getLocalExecucaoTexto() }}</p>
                                            @endif
                                            @if($forcing->observacoes_execucao)
                                                <p class="mb-0"><strong>Observações:</strong> {{ $forcing->observacoes_execucao }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Atenção:</strong> Você só pode editar o título e a descrição do forcing. 
                        Para alterar status ou informações de execução, use as ações específicas na lista de forcing.
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('forcing.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <div>
                            <a href="{{ route('forcing.show', $forcing) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Visualizar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Atualizar Forcing
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
