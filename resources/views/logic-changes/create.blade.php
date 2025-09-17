@extends('layouts.app')

@section('title', 'Nova Alteração de Lógica')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus"></i> Nova Solicitação de Alteração de Lógica</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('logic-changes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label">Título da Solicitação <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo') }}" 
                                   placeholder="Ex: Alteração no sistema de alarme da caldeira">
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="solicitante" class="form-label">Solicitante <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('solicitante') is-invalid @enderror" 
                                   id="solicitante" name="solicitante" 
                                   value="{{ old('solicitante', auth()->user()->name) }}"
                                   placeholder="Nome do solicitante">
                            @error('solicitante')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="departamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                            <select class="form-select @error('departamento') is-invalid @enderror" 
                                    id="departamento" name="departamento">
                                <option value="">Selecionar departamento...</option>
                                @foreach(\App\Models\LogicChange::DEPARTAMENTOS as $dept)
                                    <option value="{{ $dept }}" {{ old('departamento') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="data_solicitacao_form" class="form-label">Data <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('data_solicitacao_form') is-invalid @enderror" 
                                   id="data_solicitacao_form" name="data_solicitacao_form" 
                                   value="{{ old('data_solicitacao_form', date('Y-m-d')) }}">
                            @error('data_solicitacao_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao_alteracao" class="form-label">Descrição da Alteração Necessária <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descricao_alteracao') is-invalid @enderror" 
                                  id="descricao_alteracao" name="descricao_alteracao" rows="4" 
                                  placeholder="Descreva detalhadamente a alteração necessária...">{{ old('descricao_alteracao') }}</textarea>
                        @error('descricao_alteracao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo_alteracao" class="form-label">Motivo da Alteração <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('motivo_alteracao') is-invalid @enderror" 
                                  id="motivo_alteracao" name="motivo_alteracao" rows="3" 
                                  placeholder="Explique o motivo da alteração e os benefícios esperados...">{{ old('motivo_alteracao') }}</textarea>
                        @error('motivo_alteracao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="arquivos" class="form-label">Arquivos Anexos</label>
                        <input type="file" class="form-control @error('arquivos.*') is-invalid @enderror" 
                               id="arquivos" name="arquivos[]" multiple 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
                        <div class="form-text">
                            Tipos permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT. Máximo: 10MB por arquivo.
                        </div>
                        @error('arquivos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <!-- Termo de Responsabilidade -->
                    <div class="mb-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Termo de Responsabilidade</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input @error('termo_aceito') is-invalid @enderror" 
                                           type="checkbox" id="termo_aceito" name="termo_aceito" 
                                           {{ old('termo_aceito') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="termo_aceito">
                                        <strong>Estou ciente das alterações acima descritas e concordo que permanecerão válidas por tempo indeterminado até que uma nova versão deste documento seja validada pelos responsáveis.</strong>
                                    </label>
                                    @error('termo_aceito')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seção de Validações -->
                    <div class="mb-4">
                        <h5 class="text-primary"><i class="fas fa-clipboard-check"></i> Campos de Validação</h5>
                        <div class="alert alert-info">
                            <p class="mb-2"><strong>Esta solicitação passará pelas seguintes aprovações:</strong></p>
                            <ul class="mb-0">
                                <li><strong>Gerente de Manutenção</strong> - Análise técnica e viabilidade</li>
                                <li><strong>Solicitante</strong> - Confirmação da solicitação</li>
                                <li><strong>Coordenador de Manutenção</strong> - Validação operacional</li>
                                <li><strong>Técnico Especialista em Automação</strong> - Aprovação técnica final</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('logic-changes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Criar Solicitação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
    
    // Preview de arquivos selecionados
    const fileInput = document.getElementById('arquivos');
    fileInput.addEventListener('change', function() {
        const files = this.files;
        if (files.length > 0) {
            let fileList = '';
            for (let i = 0; i < files.length; i++) {
                fileList += `<li>${files[i].name} (${(files[i].size / 1024 / 1024).toFixed(2)} MB)</li>`;
            }
            
            // Criar ou atualizar preview
            let preview = document.getElementById('file-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'file-preview';
                preview.className = 'mt-2';
                fileInput.parentNode.appendChild(preview);
            }
            
            preview.innerHTML = `
                <div class="alert alert-light">
                    <strong>Arquivos selecionados:</strong>
                    <ul class="mb-0 mt-2">${fileList}</ul>
                </div>
            `;
        }
    });
    
    // Controlar estado do botão de submit baseado no checkbox
    const termoCheckbox = document.getElementById('termo_aceito');
    const btnSubmit = document.getElementById('btnSubmit');
    
    function updateSubmitButton() {
        if (termoCheckbox.checked) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-primary');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.remove('btn-primary');
            btnSubmit.classList.add('btn-secondary');
        }
    }
    
    // Estado inicial
    updateSubmitButton();
    
    // Listener para mudanças no checkbox
    termoCheckbox.addEventListener('change', updateSubmitButton);
    
    // Validação no submit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!termoCheckbox.checked) {
            e.preventDefault();
            alert('Você deve aceitar os termos de responsabilidade para continuar.');
            termoCheckbox.focus();
        }
    });
});
</script>
@endsection
