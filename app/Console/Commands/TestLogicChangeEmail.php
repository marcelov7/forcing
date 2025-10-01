<?php

namespace App\Console\Commands;

use App\Models\LogicChange;
use App\Mail\LogicChangeImplemented;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestLogicChangeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:logic-change-email {logic_change_id} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o envio de email de alteração de lógica implementada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logicChangeId = $this->argument('logic_change_id');
        $email = $this->argument('email');

        // Buscar a alteração de lógica
        $logicChange = LogicChange::find($logicChangeId);

        if (!$logicChange) {
            $this->error("Alteração de lógica com ID {$logicChangeId} não encontrada.");
            return 1;
        }

        $this->info("Enviando email de teste para: {$email}");
        $this->info("Alteração de lógica: #{$logicChange->id} - {$logicChange->titulo}");

        try {
            Mail::to($email)->send(new LogicChangeImplemented($logicChange));
            $this->info("✅ Email enviado com sucesso!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar email: " . $e->getMessage());
            return 1;
        }
    }
}