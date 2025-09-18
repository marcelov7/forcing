@echo off
REM Script para Windows (XAMPP/WAMP) - Configurar permissões após deploy

echo 🚀 Configurando projeto Laravel no Windows...

REM Definir diretório do projeto
set PROJECT_DIR=%~dp0

echo 📁 Limpando cache do Laravel...
cd /d "%PROJECT_DIR%"

REM Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo 🔄 Recriando caches otimizados...

REM Recriar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo 📦 Otimizando Composer...
composer dump-autoload --optimize

echo 🔐 Configurando permissões de diretórios (Windows)...

REM No Windows, garantir que os diretórios existam e sejam acessíveis
if not exist "storage\logs" mkdir "storage\logs"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

REM Dar permissões completas para o usuário atual nos diretórios críticos
icacls "storage" /grant:r "%USERNAME%:(OI)(CI)F" /T
icacls "bootstrap\cache" /grant:r "%USERNAME%:(OI)(CI)F" /T
icacls "public\build" /grant:r "%USERNAME%:(OI)(CI)F" /T

echo ✅ Configuração concluída!
echo 📊 Diretórios configurados:
echo    - storage/ (permissão total)
echo    - bootstrap/cache/ (permissão total)  
echo    - public/build/ (permissão total)

echo 🔍 Verificando estrutura de diretórios...
dir storage
dir bootstrap\cache

pause
