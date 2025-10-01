<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Lógica Implementada</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header .icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .content {
            padding: 30px;
        }
        
        .alert {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #0c5460;
        }
        
        .info-section {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .label {
            font-weight: 600;
            color: #495057;
            min-width: 140px;
        }
        
        .value {
            color: #212529;
            text-align: right;
            flex: 1;
        }
        
        .status-badge {
            background-color: #28a745;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .description-box {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .description-box h3 {
            margin-top: 0;
            color: #495057;
            font-size: 16px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        
        .observations {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .observations h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 14px;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        
        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 12px;
        }
        
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 15px 0;
            transition: background-color 0.3s ease;
        }
        
        .button:hover {
            background-color: #0056b3;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 0;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="icon">✅</span>
            <h1>Alteração de Lógica Implementada</h1>
            <p>Sua solicitação foi processada com sucesso</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>🎉 Boa notícia!</strong> A alteração de lógica que você solicitou foi implementada e está ativa no sistema.
            </div>
            
            <div class="info-section">
                <h2 style="margin-top: 0; color: #28a745; font-size: 18px;">📋 Detalhes da Implementação</h2>
                
                <div class="info-row">
                    <span class="label">ID da Solicitação:</span>
                    <span class="value"><strong>#{{ $logicChange->id }}</strong></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Título:</span>
                    <span class="value">{{ $logicChange->titulo }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value"><span class="status-badge">IMPLEMENTADO</span></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Solicitante:</span>
                    <span class="value">{{ $logicChange->solicitante }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Departamento:</span>
                    <span class="value">{{ $logicChange->departamento }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Data da Solicitação:</span>
                    <span class="value">{{ $logicChange->data_solicitacao_form ? $logicChange->data_solicitacao_form->format('d/m/Y') : 'Não informada' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Data de Implementação:</span>
                    <span class="value"><strong>{{ $dataImplementacao ? \Carbon\Carbon::parse($dataImplementacao)->format('d/m/Y') : now()->format('d/m/Y') }}</strong></span>
                </div>
                
                @if($implementador)
                <div class="info-row">
                    <span class="label">Implementado por:</span>
                    <span class="value">{{ $implementador->name }}</span>
                </div>
                @endif
            </div>
            
            <div class="description-box">
                <h3>📝 Descrição da Alteração Solicitada</h3>
                <p>{{ $logicChange->descricao_alteracao }}</p>
            </div>
            
            @if($logicChange->motivo_alteracao)
            <div class="description-box">
                <h3>🎯 Motivo da Alteração</h3>
                <p>{{ $logicChange->motivo_alteracao }}</p>
            </div>
            @endif
            
            @if($observacoes)
            <div class="observations">
                <h4>💬 Observações da Implementação</h4>
                <p>{{ $observacoes }}</p>
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('logic-changes.show', $logicChange->id) }}" class="button">
                    🔍 Visualizar Detalhes Completos
                </a>
            </div>
            
            <div class="info-section">
                <h3 style="margin-top: 0; color: #495057;">ℹ️ O que isso significa?</h3>
                <p>✅ <strong>A alteração foi aplicada:</strong> As modificações solicitadas já estão ativas no sistema.</p>
                <p>🔄 <strong>Funcionamento normal:</strong> Você pode utilizar normalmente as funcionalidades alteradas.</p>
                <p>📞 <strong>Suporte disponível:</strong> Em caso de dúvidas, entre em contato com o suporte técnico.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>
                <strong>Sistema de Controle de Forcing</strong><br>
                Este é um email automático. Não responda a esta mensagem.<br>
                Para suporte, utilize os canais oficiais de atendimento.
            </p>
            <p style="margin-top: 10px; color: #adb5bd;">
                Gerado automaticamente em {{ now()->format('d/m/Y \à\s H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>