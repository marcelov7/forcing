<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class DebugEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'debug:email-config';

    /**
     * The console command description.
     */
    protected $description = 'Debug da configuração de email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG DA CONFIGURAÇÃO DE EMAIL ===');
        
        // Verificar configurações
        $this->info('MAIL_MAILER: ' . Config::get('mail.default'));
        $this->info('MAIL_HOST: ' . Config::get('mail.mailers.smtp.host'));
        $this->info('MAIL_PORT: ' . Config::get('mail.mailers.smtp.port'));
        $this->info('MAIL_USERNAME: ' . Config::get('mail.mailers.smtp.username'));
        $this->info('MAIL_ENCRYPTION: ' . Config::get('mail.mailers.smtp.encryption'));
        $this->info('MAIL_FROM_ADDRESS: ' . Config::get('mail.from.address'));
        $this->info('MAIL_FROM_NAME: ' . Config::get('mail.from.name'));
        
        $this->info('');
        $this->info('=== TESTE DE ENVIO SIMPLES ===');
        
        try {
            Mail::raw('Este é um teste de email básico.', function ($message) {
                $message->to('marelo.vine@gmail.com')
                       ->subject('Teste de Email - Debug');
            });
            
            $this->info('✅ Email de teste enviado com sucesso!');
        } catch (\Exception $e) {
            $this->error('❌ Erro ao enviar email: ' . $e->getMessage());
        }
        
        return 0;
    }
}