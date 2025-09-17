<?php

namespace App\Services;

use App\Models\Forcing;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ForcingNotificationService
{
    public function notificarSolicitacaoRetirada(Forcing $forcing)
    {
        try {
            Log::info('Solicitação de retirada registrada', [
                'forcing_id' => $forcing->id,
                'tag' => $forcing->tag,
                'solicitado_por' => $forcing->solicitado_retirada_por,
                'data_solicitacao' => $forcing->data_solicitacao_retirada,
                'timestamp' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao processar notificação de solicitação de retirada: ' . $e->getMessage());
            return false;
        }
    }

    public function notificarForcingCriadoParaLiberador(Forcing $forcing, User $liberador)
    {
        try {
            Log::info('Forcing criado para liberador', [
                'forcing_id' => $forcing->id,
                'tag' => $forcing->tag,
                'liberador_id' => $liberador->id,
                'liberador_nome' => $liberador->name
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao notificar liberador: ' . $e->getMessage());
            return false;
        }
    }

    public function notificarForcingLiberado(Forcing $forcing)
    {
        try {
            Log::info('Forcing liberado', [
                'forcing_id' => $forcing->id,
                'tag' => $forcing->tag
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao notificar forcing liberado: ' . $e->getMessage());
            return false;
        }
    }

    public function notificarForcingExecutado(Forcing $forcing)
    {
        try {
            Log::info('Forcing executado', [
                'forcing_id' => $forcing->id,
                'tag' => $forcing->tag
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao notificar forcing executado: ' . $e->getMessage());
            return false;
        }
    }

    public function notificarConfirmacaoRetirada(Forcing $forcing)
    {
        try {
            Log::info('Confirmação de retirada', [
                'forcing_id' => $forcing->id,
                'tag' => $forcing->tag
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao notificar confirmação de retirada: ' . $e->getMessage());
            return false;
        }
    }
}
