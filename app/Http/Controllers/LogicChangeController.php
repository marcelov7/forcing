<?php

namespace App\Http\Controllers;

use App\Models\LogicChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LogicChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = LogicChange::with(['user', 'tecnico', 'unit']);

        // Filtro por unidade (multi-tenant)
        if (!$user->isSuperAdmin()) {
            if ($user->unit_id) {
                $query->where('unit_id', $user->unit_id);
            } else {
                // Se usuário não tem unidade, mostrar apenas suas próprias solicitações
                $query->where('user_id', $user->id);
            }
        }

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('solicitante', 'LIKE', "%{$search}%")
                  ->orWhere('departamento', 'LIKE', "%{$search}%")
                  ->orWhere('descricao_alteracao', 'LIKE', "%{$search}%")
                  ->orWhere('motivo_alteracao', 'LIKE', "%{$search}%");
            });
        }

        $logicChanges = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('logic-changes.index', compact('logicChanges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logic-changes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'solicitante' => 'required|string|max:255',
            'descricao_alteracao' => 'required|string',
            'departamento' => 'required|string|max:255',
            'data_solicitacao_form' => 'required|date',
            'motivo_alteracao' => 'required|string',
            'termo_aceito' => 'required|accepted',
            'arquivos.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'solicitante.required' => 'O nome do solicitante é obrigatório.',
            'descricao_alteracao.required' => 'A descrição da alteração é obrigatória.',
            'departamento.required' => 'O departamento é obrigatório.',
            'data_solicitacao_form.required' => 'A data é obrigatória.',
            'data_solicitacao_form.date' => 'A data deve ser uma data válida.',
            'motivo_alteracao.required' => 'O motivo da alteração é obrigatório.',
            'termo_aceito.required' => 'Você deve aceitar os termos.',
            'termo_aceito.accepted' => 'Você deve aceitar os termos.',
            'arquivos.*.max' => 'Cada arquivo deve ter no máximo 10MB.',
            'arquivos.*.mimes' => 'Tipos de arquivo permitidos: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT.',
        ]);

        $user = Auth::user();

        // Processar upload de arquivos
        $arquivos = [];
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $path = $arquivo->store('logic-changes', 'public');
                $arquivos[] = [
                    'nome' => $arquivo->getClientOriginalName(),
                    'path' => $path,
                    'size' => $arquivo->getSize(),
                    'tipo' => $arquivo->getClientMimeType(),
                ];
            }
        }

        LogicChange::create([
            'titulo' => $request->titulo,
            'solicitante' => $request->solicitante,
            'descricao_alteracao' => $request->descricao_alteracao,
            'departamento' => $request->departamento,
            'data_solicitacao_form' => $request->data_solicitacao_form,
            'motivo_alteracao' => $request->motivo_alteracao,
            'termo_aceito' => $request->has('termo_aceito'),
            'user_id' => $user->id,
            'unit_id' => $user->unit_id ?? null, // Permitir null se usuário não tem unidade
            'arquivos_anexos' => $arquivos,
        ]);

        return redirect()->route('logic-changes.index')
                        ->with('success', 'Solicitação de alteração de lógica criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogicChange $logicChange)
    {
        $this->authorize('view', $logicChange);
        
        $logicChange->load(['user', 'tecnico', 'unit']);
        
        return view('logic-changes.show', compact('logicChange'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogicChange $logicChange)
    {
        $this->authorize('update', $logicChange);

        if (!$logicChange->podeSerEditado()) {
            return redirect()->route('logic-changes.show', $logicChange)
                           ->with('error', 'Esta solicitação não pode mais ser editada.');
        }

        return view('logic-changes.edit', compact('logicChange'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogicChange $logicChange)
    {
        $this->authorize('update', $logicChange);

        if (!$logicChange->podeSerEditado()) {
            return redirect()->route('logic-changes.show', $logicChange)
                           ->with('error', 'Esta solicitação não pode mais ser editada.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'solicitante' => 'required|string|max:255',
            'descricao_alteracao' => 'required|string',
            'departamento' => 'required|string|max:255',
            'data_solicitacao_form' => 'required|date',
            'motivo_alteracao' => 'required|string',
            'termo_aceito' => 'required|accepted',
            'arquivos.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ]);

        // Processar novos arquivos
        $arquivosExistentes = $logicChange->arquivos_anexos ?? [];
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $path = $arquivo->store('logic-changes', 'public');
                $arquivosExistentes[] = [
                    'nome' => $arquivo->getClientOriginalName(),
                    'path' => $path,
                    'size' => $arquivo->getSize(),
                    'tipo' => $arquivo->getClientMimeType(),
                ];
            }
        }

        $logicChange->update([
            'titulo' => $request->titulo,
            'solicitante' => $request->solicitante,
            'descricao_alteracao' => $request->descricao_alteracao,
            'departamento' => $request->departamento,
            'data_solicitacao_form' => $request->data_solicitacao_form,
            'motivo_alteracao' => $request->motivo_alteracao,
            'termo_aceito' => $request->has('termo_aceito'),
            'arquivos_anexos' => $arquivosExistentes,
        ]);

        return redirect()->route('logic-changes.show', $logicChange)
                        ->with('success', 'Solicitação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogicChange $logicChange)
    {
        $this->authorize('delete', $logicChange);

        // Remover arquivos do storage
        if ($logicChange->arquivos_anexos) {
            foreach ($logicChange->arquivos_anexos as $arquivo) {
                Storage::disk('public')->delete($arquivo['path']);
            }
        }

        $logicChange->delete();

        return redirect()->route('logic-changes.index')
                        ->with('success', 'Solicitação removida com sucesso!');
    }


    /**
     * Remover arquivo anexo
     */
    public function removeFile(Request $request, LogicChange $logicChange)
    {
        $this->authorize('update', $logicChange);

        $fileIndex = $request->input('file_index');
        $arquivos = $logicChange->arquivos_anexos ?? [];

        if (isset($arquivos[$fileIndex])) {
            // Remover arquivo do storage
            Storage::disk('public')->delete($arquivos[$fileIndex]['path']);
            
            // Remover do array
            unset($arquivos[$fileIndex]);
            
            // Reindexar array
            $arquivos = array_values($arquivos);
            
            $logicChange->update(['arquivos_anexos' => $arquivos]);
        }

        return redirect()->back()->with('success', 'Arquivo removido com sucesso!');
    }

    /**
     * Aprovação do Gerente de Manutenção
     */
    public function approveAsManager(Request $request, LogicChange $logicChange)
    {
        $this->authorize('approveAsManager', $logicChange);

        $logicChange->update([
            'aprovacao_gerente_manutencao' => now(),
            'gerente_manutencao_id' => auth()->id(),
            'observacoes_gerente_manutencao' => $request->observacoes_gerente_manutencao,
        ]);

        // Atualizar status automaticamente
        $this->updateStatusBasedOnApprovals($logicChange);

        return redirect()->route('logic-changes.show', $logicChange)
                        ->with('success', 'Aprovação do Gerente de Manutenção registrada com sucesso!');
    }

    /**
     * Aprovação do Coordenador de Manutenção
     */
    public function approveAsCoordinator(Request $request, LogicChange $logicChange)
    {
        $this->authorize('approveAsCoordinator', $logicChange);

        $logicChange->update([
            'aprovacao_coordenador_manutencao' => now(),
            'coordenador_manutencao_id' => auth()->id(),
            'observacoes_coordenador_manutencao' => $request->observacoes_coordenador_manutencao,
        ]);

        // Atualizar status automaticamente
        $this->updateStatusBasedOnApprovals($logicChange);

        return redirect()->route('logic-changes.show', $logicChange)
                        ->with('success', 'Aprovação do Coordenador de Manutenção registrada com sucesso!');
    }

    /**
     * Aprovação do Técnico Especialista
     */
    public function approveAsSpecialist(Request $request, LogicChange $logicChange)
    {
        $this->authorize('approveAsSpecialist', $logicChange);

        $logicChange->update([
            'aprovacao_tecnico_especialista' => now(),
            'tecnico_especialista_id' => auth()->id(),
            'observacoes_tecnico_especialista' => $request->observacoes_tecnico_especialista,
        ]);

        // Atualizar status automaticamente
        $this->updateStatusBasedOnApprovals($logicChange);

        return redirect()->route('logic-changes.show', $logicChange)
                        ->with('success', 'Aprovação do Técnico Especialista registrada com sucesso!');
    }

    /**
     * Marcar como implementado (apenas admins)
     */
    public function markAsImplemented(Request $request, LogicChange $logicChange)
    {
        $this->authorize('markAsImplemented', $logicChange);

        // Verificar se pode ser implementado
        if (!$logicChange->podeSerImplementado()) {
            return redirect()->route('logic-changes.show', $logicChange)
                           ->with('error', 'Esta solicitação não pode ser marcada como implementada.');
        }

        $request->validate([
            'data_implementacao' => 'required|date',
            'observacoes_implementacao' => 'nullable|string|max:1000',
        ]);

        $logicChange->update([
            'status' => LogicChange::STATUS_IMPLEMENTADO,
            'data_implementacao' => $request->data_implementacao,
            'observacoes_implementacao' => $request->observacoes_implementacao,
            'implementado_por_id' => auth()->id(),
            'implementado_em' => now(),
        ]);

        return redirect()->route('logic-changes.show', $logicChange)
                        ->with('success', 'Alteração marcada como implementada com sucesso!');
    }

    /**
     * Página de aprovação do Gerente (Mobile)
     */
    public function approveAsManagerPage(LogicChange $logicChange)
    {
        $this->authorize('approveAsManager', $logicChange);
        return view('logic-changes.approve-manager', compact('logicChange'));
    }

    /**
     * Página de aprovação do Coordenador (Mobile)
     */
    public function approveAsCoordinatorPage(LogicChange $logicChange)
    {
        $this->authorize('approveAsCoordinator', $logicChange);
        return view('logic-changes.approve-coordinator', compact('logicChange'));
    }

    /**
     * Página de aprovação do Especialista (Mobile)
     */
    public function approveAsSpecialistPage(LogicChange $logicChange)
    {
        $this->authorize('approveAsSpecialist', $logicChange);
        return view('logic-changes.approve-specialist', compact('logicChange'));
    }

    /**
     * Página de implementação (Mobile)
     */
    public function markAsImplementedPage(LogicChange $logicChange)
    {
        $this->authorize('markAsImplemented', $logicChange);
        
        if (!$logicChange->podeSerImplementado()) {
            return redirect()->route('logic-changes.show', $logicChange)
                           ->with('error', 'Esta solicitação não pode ser marcada como implementada.');
        }

        return view('logic-changes.mark-implemented', compact('logicChange'));
    }

    /**
     * Atualizar status automaticamente baseado nas aprovações
     */
    private function updateStatusBasedOnApprovals(LogicChange $logicChange)
    {
        // Recarregar o modelo para ter os dados mais recentes
        $logicChange->refresh();
        
        // Se todas as aprovações foram concedidas, marcar como aprovado
        if ($logicChange->todasAprovacoesCompletas()) {
            $logicChange->update(['status' => LogicChange::STATUS_APROVADO]);
        }
        // Se tem pelo menos uma aprovação, marcar como em análise
        elseif ($logicChange->aprovacao_gerente_manutencao || 
                $logicChange->aprovacao_coordenador_manutencao || 
                $logicChange->aprovacao_tecnico_especialista) {
            if ($logicChange->status === LogicChange::STATUS_PENDENTE) {
                $logicChange->update(['status' => LogicChange::STATUS_EM_ANALISE]);
            }
        }
    }

}
