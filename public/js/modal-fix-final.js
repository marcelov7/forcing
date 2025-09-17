// MODAL FIX FINAL - Com limpeza completa entre aberturas
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🎯 MODAL FIX FINAL - Com limpeza de estado');
    
    const Z_INDEX = {
        MODAL: 50000,
        MODAL_BACKDROP: 49999
    };
    
    let currentModal = null;
    let modalInstances = new Map();
    
    // Função para limpar completamente o estado da modal
    function cleanModalState() {
        console.log('🧹 Limpando estado da modal...');
        
        // Remover todos os backdrops órfãos
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            backdrop.remove();
        });
        
        // Limpar classes do body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Resetar z-index de todas as modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.zIndex = '';
            modal.style.display = '';
        });
        
        currentModal = null;
        
        console.log('✅ Estado limpo');
    }
    
    // Função para preparar modal para abertura
    function prepareModal(modal) {
        console.log('🔧 Preparando modal:', modal.id);
        
        // Limpar estado anterior
        cleanModalState();
        
        // Configurar z-index
        modal.style.zIndex = Z_INDEX.MODAL;
        
        // Destruir instance anterior se existir
        if (modalInstances.has(modal.id)) {
            try {
                modalInstances.get(modal.id).dispose();
            } catch (e) {
                console.log('Instance já removida');
            }
            modalInstances.delete(modal.id);
        }
        
        currentModal = modal;
        
        console.log('✅ Modal preparada');
    }
    
    // Função para configurar backdrop
    function fixBackdrop() {
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = Z_INDEX.MODAL_BACKDROP;
                console.log('✅ Backdrop configurado');
            }
        }, 50);
    }
    
    // Event listeners com limpeza
    document.addEventListener('show.bs.modal', function(e) {
        console.log('🔵 Modal iniciando:', e.target.id);
        prepareModal(e.target);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('🟢 Modal aberta:', e.target.id);
        
        fixBackdrop();
        
        // Armazenar instance
        const instance = bootstrap.Modal.getInstance(e.target);
        if (instance) {
            modalInstances.set(e.target.id, instance);
        }
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('🟡 Modal fechando:', e.target.id);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('🔴 Modal fechada:', e.target.id);
        
        // Limpeza forçada após fechamento
        setTimeout(() => {
            cleanModalState();
        }, 100);
    });
    
    // Limpeza em caso de erro
    window.addEventListener('beforeunload', function() {
        cleanModalState();
    });
    
    // Função para abrir modal com limpeza prévia
    window.openModalSafe = function(modalId) {
        console.log('🚀 Abrindo modal segura:', modalId);
        
        // Limpeza preventiva
        cleanModalState();
        
        const modal = document.getElementById(modalId);
        if (modal) {
            prepareModal(modal);
            
            const bsModal = new bootstrap.Modal(modal, {
                backdrop: 'static',
                keyboard: false
            });
            
            modalInstances.set(modalId, bsModal);
            bsModal.show();
        }
    };
    
    // Função para fechar modal com limpeza
    window.closeModalSafe = function(modalId) {
        console.log('🔒 Fechando modal segura:', modalId);
        
        const modal = document.getElementById(modalId);
        if (modal) {
            const instance = modalInstances.get(modalId);
            if (instance) {
                instance.hide();
            } else {
                const fallbackInstance = bootstrap.Modal.getInstance(modal);
                if (fallbackInstance) {
                    fallbackInstance.hide();
                }
            }
        }
        
        // Limpeza forçada
        setTimeout(() => {
            cleanModalState();
        }, 300);
    };
    
    // Interceptar cliques em botões data-bs-toggle
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-bs-toggle="modal"]') || e.target.closest('[data-bs-toggle="modal"]')) {
            const button = e.target.matches('[data-bs-toggle="modal"]') ? e.target : e.target.closest('[data-bs-toggle="modal"]');
            const targetId = button.getAttribute('data-bs-target');
            
            if (targetId) {
                console.log('🎯 Interceptando abertura via data-bs-toggle:', targetId);
                e.preventDefault();
                
                // Remover # se presente
                const modalId = targetId.replace('#', '');
                openModalSafe(modalId);
            }
        }
    });
    
    // Limpeza inicial
    cleanModalState();
    
    console.log('🎯 Modal Fix Final inicializado - Com limpeza automática');
}); 