@echo off
echo ========================================
echo    DEPLOY VIA SSH - SISTEMA FORCING
echo ========================================
echo.

echo 1. Fazendo commit das alterações locais...
git add .
git commit -m "feat: Melhorar cards de status para mostrar contadores gerais

- Corrigir contagem de cards para mostrar dados gerais ao invés de por página
- Implementar contadores independentes para cada status
- Adicionar atualização via AJAX dos cards
- Melhorar UX com dados precisos e em tempo real
- Respeitar filtros de unidade (multi-tenant)"

echo.
echo 2. Conectando via SSH para fazer deploy...
echo Usando: ssh devaxis-forcing@31.97.168.137
echo.

echo 3. Comandos que serão executados no servidor:
echo cd /home/devaxis-forcing/htdocs/forcing.devaxis.com.br
echo git pull origin main
echo composer install --no-dev --optimize-autoloader
echo php artisan config:cache
echo php artisan route:cache
echo php artisan view:cache
echo.

echo 4. Para executar o deploy, execute manualmente:
echo ssh root@31.97.168.137
echo.
echo E depois execute os comandos acima no servidor.
echo.
pause
