@extends('layouts.app')

@section('title', 'Editar Alteração de Lógica #' . $logicChange->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Editar Solicitação #{{ $logicChange->id }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('logic-changes.update', $logicChange) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="titulo" class="form-label">Título da Solicitação <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo', $logicChange->titulo) }}" 
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
                                   value="{{ old('solicitante', $logicChange->solicitante) }}"
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
                                    <option value="{{ $dept }}" {{ old('departamento', $logicChange->departamento) == $dept ? 'selected' : '' }}>
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
                                   value="{{ old('data_solicitacao_form', $logicChange->data_solicitacao_form ? $logicChange->data_solicitacao_form->format('Y-m-d') : '') }}">
                            @error('data_solicitacao_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label for="descricao_alteracao" class="form-label">Descrição da Alteração Necessária <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descricao_alteracao') is-invalid @enderror" 
                                  id="descricao_alteracao" name="descricao_alteracao" rows="4" 
                                  placeholder="Descreva detalhadamente a alteração necessária...">{{ old('descricao_alteracao', $logicChange->descricao_alteracao) }}</textarea>
                        @error('descricao_alteracao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo_alteracao" class="form-label">Motivo da Alteração <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('motivo_alteracao') is-invalid @enderror" 
                                  id="motivo_alteracao" name="motivo_alteracao" rows="3" 
                                  placeholder="Explique o motivo da alteração e os benefícios esperados...">{{ old('motivo_alteracao', $logicChange->motivo_alteracao) }}</textarea>
                        @error('motivo_alteracao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Arquivos existentes -->
                    @if($logicChange->arquivos_anexos && count($logicChange->arquivos_anexos) > 0)
                        <div class="mb-3">
                            <label class="form-label">Arquivos Anexos Atuais</label>
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
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="removeFile({{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="arquivos" class="form-label">Adicionar Novos Arquivos</label>
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
                                           {{ old('termo_aceito', $logicChange->termo_aceito) ? 'checked' : '' }}>
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
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> Esta solicitação está com status "{{ $logicChange->getStatusFormatado() }}". 
                        Algumas alterações podem não ser permitidas após a análise técnica.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('logic-changes.show', $logicChange) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Atualizar Solicitação
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
        // Set initial height
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
        
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
                    <strong>Novos arquivos selecionados:</strong>
                    <ul class="mb-0 mt-2">${fileList}</ul>
                </div>
            `;
        }
    });
});

function removeFile(index) {
    if (confirm('Tem certeza que deseja remover este arquivo?')) {
        // Criar form para enviar requisição
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logic-changes.remove-file", $logicChange) }}';
        
        // CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // File index
        const fileIndex = document.createElement('input');
        fileIndex.type = 'hidden';
        fileIndex.name = 'file_index';
        fileIndex.value = index;
        form.appendChild(fileIndex);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
