// VERSÃO DE TESTE - SEM BOTÃO DALTONISMO
// Para testar se o conflito está no botão daltonismo

document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🧪 TESTE SEM DALTONISMO - Modal Fix carregado');
    
    // Z-index simples apenas para modal
    const Z_INDEX = {
        MODAL: 50000,
        MODAL_BACKDROP: 49999
    };
    
    // Fix APENAS para z-index da modal - SEM mexer no daltonismo
    function fixModalZIndex(modal) {
        if (!modal) return;
        
        modal.style.zIndex = Z_INDEX.MODAL;
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.zIndex = Z_INDEX.MODAL_BACKDROP;
        }
        
        console.log('✅ Z-index da modal configurado:', Z_INDEX.MODAL);
    }
    
    // Event listeners APENAS para modal - SEM daltonismo
    document.addEventListener('show.bs.modal', function(e) {
        console.log('🔵 Modal abrindo:', e.target.id);
        fixModalZIndex(e.target);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('🟢 Modal aberta:', e.target.id);
        fixModalZIndex(e.target);
    });
    
    document.addEventListener('hide.bs.modal', function(e) {
        console.log('🟡 Modal fechando:', e.target.id);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('🔴 Modal fechada:', e.target.id);
    });
    
    // Função simples para abrir modal
    window.openModalSafe = function(modalId) {
        console.log('🚀 Abrindo modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    };
    
    console.log('🧪 TESTE INICIALIZADO - Sem interferência do botão daltonismo');
}); 