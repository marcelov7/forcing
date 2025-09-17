@extends('layouts.app')

@section('title', 'Novo Forcing')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus"></i> Novo Forcing</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('forcing.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="tag" class="form-label">TAG <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tag') is-invalid @enderror" 
                               id="tag" name="tag" value="{{ old('tag') }}" required 
                               placeholder="Ex: TAG-001, PUMP-01...">
                        @error('tag')
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
                                    <option value="desativado" {{ old('situacao_equipamento') == 'desativado' ? 'selected' : '' }}>Desativado</option>
                                    <option value="ativacao_futura" {{ old('situacao_equipamento') == 'ativacao_futura' ? 'selected' : '' }}>Ativação Futura</option>
                                    <option value="em_atividade" {{ old('situacao_equipamento') == 'em_atividade' ? 'selected' : '' }}>Em Atividade</option>
                                </select>
                                @error('situacao_equipamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('area') is-invalid @enderror" 
                                       id="area" name="area" value="{{ old('area') }}" required 
                                       placeholder="Ex: Produção, Manutenção, Utilidades...">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao_equipamento" class="form-label">Descrição do Equipamento <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descricao_equipamento') is-invalid @enderror" 
                                  id="descricao_equipamento" name="descricao_equipamento" rows="3" required 
                                  placeholder="Descreva detalhadamente o equipamento...">{{ old('descricao_equipamento') }}</textarea>
                        @error('descricao_equipamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-user"></i> Informações do Solicitante</h6>
                                    <p class="mb-1"><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                                    <p class="mb-1"><strong>Empresa:</strong> {{ auth()->user()->empresa }}</p>
                                    <p class="mb-1"><strong>Setor:</strong> {{ auth()->user()->setor }}</p>
                                    <p class="mb-0"><strong>E-mail:</strong> {{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning">
                                <div class="card-body">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Atenção</h6>
                                    <p class="mb-0">Este forcing será criado com status <strong>"FORÇADO"</strong> e ficará ativo até que um liberador ou administrador o retire do sistema.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('forcing.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Criar Forcing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
