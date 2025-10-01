#!/bin/bash

echo "========================================"
echo "    DEPLOY VIA SSH - SISTEMA FORCING"
echo "========================================"
echo

# 1. Fazer commit local
echo "1. Fazendo commit das alterações locais..."
git add .
git commit -m "feat: Melhorar cards de status para mostrar contadores gerais

- Corrigir contagem de cards para mostrar dados gerais ao invés de por página
- Implementar contadores independentes para cada status
- Adicionar atualização via AJAX dos cards
- Melhorar UX com dados precisos e em tempo real
- Respeitar filtros de unidade (multi-tenant)"

# 2. Fazer push para o repositório
echo "2. Fazendo push para o repositório..."
git push origin main

# 3. Conectar via SSH e fazer deploy
echo "3. Conectando via SSH e fazendo deploy..."
ssh root@31.97.168.137 << 'EOF'
    echo "Conectado ao servidor. Iniciando deploy..."
    
    # Navegar para o diretório do projeto
    cd /home/devaxis-forcing/htdocs/forcing.devaxis.com.br
    
    # Fazer backup da versão atual
    echo "Fazendo backup da versão atual..."
    cp -r . ../backup-$(date +%Y%m%d-%H%M%S)/
    
    # Atualizar código do Git
    echo "Atualizando código do Git..."
    git pull origin main
    
    # Instalar dependências
    echo "Instalando dependências..."
    composer install --no-dev --optimize-autoloader
    
    # Limpar e recriar caches
    echo "Otimizando aplicação..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Definir permissões corretas
    echo "Definindo permissões..."
    chown -R devaxis-forcing:devaxis-forcing .
    chmod -R 755 storage bootstrap/cache
    
    echo "Deploy concluído com sucesso!"
    echo "Sistema atualizado em: $(date)"
EOF

echo "Deploy finalizado!"
