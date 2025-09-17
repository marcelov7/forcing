// Modal Fix V4 - Versão Simplificada e Funcional
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('Modal Fix V4 carregado - Versão Simplificada');
    
    // Z-index hierarchy simples
    const Z_INDEX = {
        MODAL: 50000,
        MODAL_BACKDROP: 49999,
        COLORBLIND_NORMAL: 1050,
        COLORBLIND_HIDDEN: 999
    };
    
    // Função simples para controlar botão daltonismo
    function controlColorblindButton(hide = false) {
        const colorblindBtn = document.getElementById('colorblind-toggle-btn');
        if (colorblindBtn) {
            if (hide) {
                colorblindBtn.style.zIndex = Z_INDEX.COLORBLIND_HIDDEN;
                colorblindBtn.style.opacity = '0.3';
                colorblindBtn.style.pointerEvents = 'none';
                console.log('Botão daltonismo ocultado');
            } else {
                colorblindBtn.style.zIndex = Z_INDEX.COLORBLIND_NORMAL;
                colorblindBtn.style.opacity = '';
                colorblindBtn.style.pointerEvents = '';
                console.log('Botão daltonismo restaurado');
            }
        }
    }
    
    // Fix simples para z-index da modal
    function fixModalZIndex(modal) {
        if (!modal) return;
        
        // Aplicar z-index alto apenas na modal
        modal.style.zIndex = Z_INDEX.MODAL;
        
        // Backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.zIndex = Z_INDEX.MODAL_BACKDROP;
        }
        
        console.log('Z-index da modal configurado:', Z_INDEX.MODAL);
    }
    
    // Event listeners APENAS para controle do botão daltonismo
    document.addEventListener('show.bs.modal', function(e) {
        console.log('Modal abrindo:', e.target.id);
        controlColorblindButton(true);
        fixModalZIndex(e.target);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('Modal aberta:', e.target.id);
        fixModalZIndex(e.target);
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('Modal fechando:', e.target.id);
        controlColorblindButton(false);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('Modal fechada:', e.target.id);
        controlColorblindButton(false);
    });
    
    // Funções opcionais para uso manual
    window.openModalSafe = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    };
    
    window.closeModalSafe = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    };
    
    console.log('Modal Fix V4 inicializado - Foco apenas no z-index');
}); 