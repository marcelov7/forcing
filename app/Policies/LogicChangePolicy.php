<?php

namespace App\Policies;

use App\Models\LogicChange;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LogicChangePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos os usuários autenticados podem ver a lista
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode ver tudo (sem restrição de tenant)
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Usuário pode ver suas próprias solicitações (independente da unidade)
        if ($user->id === $logicChange->user_id) {
            return true;
        }
        
        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        // Se a solicitação tem unit_id definido, o usuário deve ser da mesma unidade
        if ($logicChange->unit_id) {
            // Se usuário não tem unidade, não pode ver solicitações de outras unidades
            if (!$user->unit_id) {
                return false;
            }
            
            // Devem ser da mesma unidade
            if ($user->unit_id !== $logicChange->unit_id) {
                return false;
            }
        }
        
        // Se chegou até aqui, são da mesma unidade ou solicitação sem unidade
        
        // Admin pode ver tudo da sua unidade
        if ($user->isAdmin()) {
            return true;
        }
        
        // Executantes podem ver solicitações da sua unidade (para aprovar)
        if ($user->isExecutante()) {
            return true;
        }
        
        // Usuários normais podem ver solicitações da mesma unidade
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Todos os usuários autenticados podem criar solicitações
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode editar tudo
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Usuário pode editar suas próprias solicitações (se ainda editável)
        if ($user->id === $logicChange->user_id && $logicChange->podeSerEditado()) {
            return true;
        }
        
        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }
        
        // Admin pode editar solicitações da sua unidade
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode deletar tudo
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admin pode deletar da sua unidade
        if ($user->isAdmin()) {
            return true;
        }
        
        // Usuário pode deletar suas próprias solicitações (se ainda editável)
        if ($user->id === $logicChange->user_id && $logicChange->podeSerEditado()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update status of the model.
     */
    public function updateStatus(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode atualizar status
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }
        
        // Admin pode atualizar status da sua unidade
        if ($user->isAdmin()) {
            return true;
        }
        
        // Técnicos podem atualizar status das solicitações da sua unidade
        if ($user->isExecutante()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LogicChange $logicChange): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LogicChange $logicChange): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can approve as manager.
     */
    public function approveAsManager(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode aprovar como qualquer perfil
        if ($user->isSuperAdmin()) {
            return true;
        }

        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }

        // Admin pode aprovar como gerente (da sua unidade)
        if ($user->isAdmin()) {
            return true;
        }

        // Liberador pode aprovar como gerente (da sua unidade)
        // Liberadores são os responsáveis por liberações e aprovações
        if ($user->isLiberador()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can approve as coordinator.
     */
    public function approveAsCoordinator(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode aprovar como qualquer perfil
        if ($user->isSuperAdmin()) {
            return true;
        }

        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }

        // Admin pode aprovar como coordenador (da sua unidade)
        if ($user->isAdmin()) {
            return true;
        }

        // Liberador pode aprovar como coordenador (da sua unidade)
        if ($user->isLiberador()) {
            return true;
        }

        // Executantes podem aprovar como coordenador (da sua unidade)
        if ($user->isExecutante()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can approve as specialist.
     */
    public function approveAsSpecialist(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode aprovar como qualquer perfil
        if ($user->isSuperAdmin()) {
            return true;
        }

        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }

        // Admin pode aprovar como especialista (da sua unidade)
        if ($user->isAdmin()) {
            return true;
        }

        // Liberador pode aprovar como especialista (da sua unidade)
        if ($user->isLiberador()) {
            return true;
        }

        // Executantes (técnicos) podem aprovar como especialista (da sua unidade)
        if ($user->isExecutante()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can mark as implemented.
     */
    public function markAsImplemented(User $user, LogicChange $logicChange): bool
    {
        // Super admin pode marcar como implementado
        if ($user->isSuperAdmin()) {
            return true;
        }

        // MULTI-TENANT: Verificar se pertencem à mesma unidade
        if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
            return false;
        }

        // Admin pode marcar como implementado (da sua unidade)
        if ($user->isAdmin()) {
            return true;
        }

        // Liberador pode marcar como implementado (da sua unidade)
        if ($user->isLiberador()) {
            return true;
        }

        // Executantes podem marcar como implementado (da sua unidade)
        // Técnicos de automação, eletricistas, eletrotécnicos, coordenadores de manutenção elétrica
        if ($user->isExecutante()) {
            return true;
        }

        return false;
    }
}
