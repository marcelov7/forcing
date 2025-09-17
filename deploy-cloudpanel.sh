#!/bin/bash
# Script de Deploy para CloudPanel
# Execute este script no servidor CloudPanel após upload dos arquivos

echo "🚀 INICIANDO DEPLOY - SISTEMA DE FORCING"
echo "======================================"

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script na raiz do projeto Laravel"
    exit 1
fi

echo "📋 1. Configurando permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

echo "🔑 2. Gerando chave da aplicação..."
if [ ! -f ".env" ]; then
    echo "⚠️ Arquivo .env não encontrado. Copiando template..."
    cp .env.cloudpanel .env
    echo "✅ Configure as credenciais do MySQL no arquivo .env"
fi

php artisan key:generate --force

echo "📦 3. Instalando dependências..."
composer install --optimize-autoloader --no-dev

echo "🗄️ 4. Executando migrações..."
php artisan migrate --force

echo "👤 5. Criando usuários iniciais..."
php artisan db:seed --class=AdminUserSeeder --force

echo "🧹 6. Limpando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ 7. Verificando sistema..."
php artisan migrate:check-mysql

echo ""
echo "🎉 DEPLOY CONCLUÍDO!"
echo "==================="
echo ""
echo "📝 PRÓXIMOS PASSOS:"
echo "1. Configure as credenciais MySQL no arquivo .env"
echo "2. Teste o acesso ao sistema"
echo "3. Verifique os e-mails da Hostinger"
echo ""
echo "👥 CREDENCIAIS PADRÃO:"
echo "• Admin: admin / admin123"
echo "• Liberador: liberador / liberador123" 
echo "• Executante: executante / executante123"
echo "• Usuário: usuario / usuario123"
echo ""
echo "📧 E-MAIL CONFIGURADO:"
echo "• Sistema: sistema@devaxis.com.br"
echo "• Limite: 85 emails/dia (Hostinger)"
echo ""
echo "🌐 Sistema pronto para uso!"
