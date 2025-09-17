// MODAL FIX FINAL V2 - Correção completa para iPhone e desktop
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🎯 MODAL FIX FINAL V2 - Correção z-index e botões');
    
    // Z-index MUITO ALTO para ficar acima de TUDO
    const Z_INDEX = {
        MODAL: 999999,
        MODAL_BACKDROP: 999998
    };
    
    let currentModal = null;
    let modalInstances = new Map();
    
    // Função para limpar completamente o estado
    function cleanModalState() {
        console.log('🧹 Limpando estado...');
        
        // Remover todos os backdrops órfãos
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });
        
        // Limpar classes do body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Resetar todas as modals
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = '';
            modal.classList.remove('show');
        });
        
        currentModal = null;
        console.log('✅ Estado limpo');
    }
    
    // Função para posicionar modal ABSOLUTAMENTE no centro
    function forceModalCentering(modal) {
        if (!modal) return;
        
        console.log('📍 Forçando centralização da modal:', modal.id);
        
        // Z-index EXTREMAMENTE ALTO
        modal.style.zIndex = Z_INDEX.MODAL;
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.padding = '20px';
        modal.style.boxSizing = 'border-box';
        
        // Dialog
        const modalDialog = modal.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.margin = '0';
            modalDialog.style.width = 'auto';
            modalDialog.style.maxWidth = '90vw';
            modalDialog.style.maxHeight = '90vh';
            modalDialog.style.position = 'relative';
            modalDialog.style.transform = 'none';
        }
        
        // Content
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.maxHeight = '85vh';
            modalContent.style.overflow = 'auto';
            modalContent.style.borderRadius = '10px';
            modalContent.style.boxShadow = '0 20px 60px rgba(0,0,0,0.5)';
        }
        
        console.log('✅ Modal centralizada com z-index:', Z_INDEX.MODAL);
    }
    
    // Função para configurar backdrop
    function setupBackdrop() {
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = Z_INDEX.MODAL_BACKDROP;
                backdrop.style.position = 'fixed';
                backdrop.style.top = '0';
                backdrop.style.left = '0';
                backdrop.style.width = '100vw';
                backdrop.style.height = '100vh';
                backdrop.style.backgroundColor = 'rgba(0,0,0,0.7)';
                console.log('✅ Backdrop configurado');
            }
        }, 50);
    }
    
    // Função para garantir que botões funcionem
    function fixModalButtons(modal) {
        if (!modal) return;
        
        console.log('🔘 Corrigindo botões da modal:', modal.id);
        
        // Botões de cancelar (data-bs-dismiss="modal")
        const cancelButtons = modal.querySelectorAll('[data-bs-dismiss="modal"]');
        cancelButtons.forEach(button => {
            // Remover listeners antigos
            button.removeEventListener('click', handleCancelClick);
            
            // Adicionar novo listener
            button.addEventListener('click', handleCancelClick);
            
            console.log('✅ Botão cancelar configurado:', button.textContent.trim());
        });
        
        // Todos os botões da modal
        const allButtons = modal.querySelectorAll('button, .btn');
        allButtons.forEach(button => {
            button.style.position = 'relative';
            button.style.zIndex = '10';
            button.style.pointerEvents = 'auto';
            
            // iOS specific fixes
            if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                button.style.webkitAppearance = 'none';
                button.style.webkitTouchCallout = 'none';
                button.style.touchAction = 'manipulation';
                button.style.minHeight = '44px';
            }
        });
        
        console.log(`✅ ${cancelButtons.length} botões cancelar + ${allButtons.length} botões configurados`);
    }
    
    // Handler para botões de cancelar
    function handleCancelClick(e) {
        console.log('❌ Botão cancelar clicado:', e.target.textContent.trim());
        
        const modal = e.target.closest('.modal');
        if (modal) {
            const instance = bootstrap.Modal.getInstance(modal) || modalInstances.get(modal.id);
            if (instance) {
                instance.hide();
            } else {
                // Fallback: fechar manualmente
                modal.classList.remove('show');
                modal.style.display = 'none';
                cleanModalState();
            }
        }
    }
    
    // Event listeners principais
    document.addEventListener('show.bs.modal', function(e) {
        console.log('🔵 Modal abrindo:', e.target.id);
        
        cleanModalState();
        currentModal = e.target;
        
        // Force positioning immediately
        setTimeout(() => {
            forceModalCentering(e.target);
            setupBackdrop();
        }, 10);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('🟢 Modal aberta:', e.target.id);
        
        // Re-force positioning
        forceModalCentering(e.target);
        setupBackdrop();
        fixModalButtons(e.target);
        
        // Store instance
        const instance = bootstrap.Modal.getInstance(e.target);
        if (instance) {
            modalInstances.set(e.target.id, instance);
        }
        
        console.log('✅ Modal totalmente configurada');
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('🟡 Modal fechando:', e.target.id);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('🔴 Modal fechada:', e.target.id);
        
        // Limpeza após fechamento
        setTimeout(() => {
            cleanModalState();
        }, 100);
    });
    
    // Interceptar cliques em botões que abrem modal
    document.addEventListener('click', function(e) {
        const modalTrigger = e.target.closest('[data-bs-toggle="modal"]');
        if (modalTrigger) {
            const targetId = modalTrigger.getAttribute('data-bs-target');
            console.log('🎯 Interceptando abertura de modal:', targetId);
            
            // Limpeza preventiva
            setTimeout(() => {
                cleanModalState();
            }, 10);
        }
    });
    
    // Função de abertura segura
    window.openModalSafe = function(modalId) {
        console.log('🚀 Abrindo modal segura:', modalId);
        
        cleanModalState();
        
        const modal = document.getElementById(modalId);
        if (modal) {
            const bsModal = new bootstrap.Modal(modal, {
                backdrop: 'static',
                keyboard: false
            });
            
            modalInstances.set(modalId, bsModal);
            bsModal.show();
        }
    };
    
    // Função de fechamento segura
    window.closeModalSafe = function(modalId) {
        console.log('🔒 Fechando modal segura:', modalId);
        
        const modal = document.getElementById(modalId);
        if (modal) {
            const instance = modalInstances.get(modalId) || bootstrap.Modal.getInstance(modal);
            if (instance) {
                instance.hide();
            }
        }
        
        setTimeout(() => {
            cleanModalState();
        }, 300);
    };
    
    // Fix para resize/orientação
    window.addEventListener('resize', function() {
        if (currentModal) {
            setTimeout(() => {
                forceModalCentering(currentModal);
            }, 100);
        }
    });
    
    window.addEventListener('orientationchange', function() {
        if (currentModal) {
            setTimeout(() => {
                forceModalCentering(currentModal);
            }, 300);
        }
    });
    
    // Limpeza inicial
    cleanModalState();
    
    console.log('🎯 Modal Fix Final V2 inicializado - Z-index:', Z_INDEX.MODAL);
}); 