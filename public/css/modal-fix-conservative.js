// MODAL FIX CONSERVADOR - Apenas o essencial
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🎯 MODAL FIX CONSERVADOR - Sem interferências');
    
    // Z-index alto apenas para modals
    const MODAL_Z_INDEX = 100000;
    const BACKDROP_Z_INDEX = 99999;
    
    // Função para aplicar fix APENAS na modal aberta
    function applyModalFix(modal) {
        if (!modal) return;
        
        console.log('🔧 Aplicando fix na modal:', modal.id);
        
        // Z-index alto apenas para esta modal
        modal.style.zIndex = MODAL_Z_INDEX;
        
        // Backdrop
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = BACKDROP_Z_INDEX;
            }
        }, 50);
        
        // Fix botões cancelar APENAS desta modal
        const cancelButtons = modal.querySelectorAll('[data-bs-dismiss="modal"]');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                console.log('❌ Cancelar clicado');
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    instance.hide();
                }
            });
        });
        
        console.log('✅ Fix aplicado na modal:', modal.id);
    }
    
    // Função para limpar apenas elementos órfãos
    function cleanOrphans() {
        // Remover apenas backdrops órfãos (sem modal correspondente aberta)
        const backdrops = document.querySelectorAll('.modal-backdrop');
        const openModals = document.querySelectorAll('.modal.show');
        
        if (backdrops.length > 0 && openModals.length === 0) {
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            console.log('🧹 Backdrops órfãos removidos');
        }
    }
    
    // Event listeners NÃO invasivos
    document.addEventListener('shown.bs.modal', function(e) {
        console.log('🟢 Modal mostrada:', e.target.id);
        applyModalFix(e.target);
    });
    
    document.addEventListener('hidden.bs.modal', function(e) {
        console.log('🔴 Modal escondida:', e.target.id);
        
        // Limpar órfãos após fechamento
        setTimeout(() => {
            cleanOrphans();
        }, 100);
    });
    
    // Limpeza inicial apenas de órfãos
    cleanOrphans();
    
    console.log('🎯 Modal Fix Conservador inicializado');
}); 