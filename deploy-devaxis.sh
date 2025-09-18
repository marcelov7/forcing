#!/bin/bash

# Script de Deploy para forcing.devaxis.com.br
# Execute este script no servidor após fazer SSH

echo "🚀 Iniciando deploy para forcing.devaxis.com.br..."

# Definir diretório do projeto
PROJECT_DIR="/home/devaxis-forcing/htdocs/forcing.devaxis.com.br"
WEB_USER="devaxis-forcing"

echo "📁 Navegando para o diretório do projeto..."
cd $PROJECT_DIR

echo "💾 Fazendo backup do .env..."
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

echo "📥 Fazendo pull das alterações do Git..."
git pull origin main

echo "📦 Instalando dependências do Composer..."
composer install --optimize-autoloader --no-dev

echo "🎨 Compilando assets..."
npm install
npm run build

echo "🔐 Configurando permissões..."
# Storage deve ser gravável pelo servidor web
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 755 public/build/

# Definir proprietário correto
chown -R $WEB_USER:$WEB_USER storage/
chown -R $WEB_USER:$WEB_USER bootstrap/cache/
chown -R $WEB_USER:$WEB_USER public/build/

# .env deve ter permissões restritas
chmod 640 .env

echo "🗄️ Executando migrações..."
php artisan migrate --force

echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Recriando caches otimizados..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Otimizando aplicação..."
php artisan optimize

echo "✅ Deploy concluído com sucesso!"
echo ""
echo "🔍 Verificações finais:"
echo "   - Versão do Laravel: $(php artisan --version)"
echo "   - Permissões storage: $(ls -la storage/ | head -3)"
echo "   - Assets compilados: $(ls -la public/build/ | head -3)"
echo ""
echo "📊 Para monitorar logs:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "🌐 Acesse: https://forcing.devaxis.com.br"
