#!/bin/bash

# Script para manter permissões corretas durante deploy
# Execute este script após fazer pull/deploy no servidor

echo "🚀 Configurando permissões para o projeto Laravel..."

# Definir o usuário do servidor web (ajuste conforme seu servidor)
WEB_USER="www-data"  # Para Apache/Nginx no Ubuntu/Debian
# WEB_USER="nginx"   # Para Nginx em CentOS/RHEL
# WEB_USER="apache"  # Para Apache em CentOS/RHEL

# Diretório do projeto (ajuste para o caminho correto no servidor)
PROJECT_DIR="/var/www/html/forcing-projeto"  # Ajuste conforme necessário

echo "📁 Configurando permissões de diretórios..."

# Permissões gerais do projeto
sudo chown -R $USER:$WEB_USER $PROJECT_DIR
sudo find $PROJECT_DIR -type f -exec chmod 644 {} \;
sudo find $PROJECT_DIR -type d -exec chmod 755 {} \;

echo "📝 Configurando permissões especiais para Laravel..."

# Storage e cache - precisam ser graváveis pelo servidor web
sudo chown -R $WEB_USER:$WEB_USER $PROJECT_DIR/storage
sudo chown -R $WEB_USER:$WEB_USER $PROJECT_DIR/bootstrap/cache
sudo chmod -R 775 $PROJECT_DIR/storage
sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Logs específicos
sudo chmod -R 775 $PROJECT_DIR/storage/logs
sudo chmod -R 775 $PROJECT_DIR/storage/framework
sudo chmod -R 775 $PROJECT_DIR/storage/app

echo "🔐 Configurando permissões de arquivos sensíveis..."

# .env deve ser legível apenas pelo proprietário e grupo
sudo chmod 640 $PROJECT_DIR/.env

# Arquivos de configuração
sudo chmod 644 $PROJECT_DIR/config/*.php

# Assets compilados
sudo chmod -R 644 $PROJECT_DIR/public/build/*
sudo chmod 755 $PROJECT_DIR/public/build

# Executáveis do Composer
sudo chmod +x $PROJECT_DIR/vendor/bin/*

echo "🔄 Limpando cache e otimizando..."

cd $PROJECT_DIR

# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar caches otimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize

echo "✅ Permissões configuradas com sucesso!"
echo "📊 Resumo das permissões aplicadas:"
echo "   - Diretórios: 755 (rwxr-xr-x)"
echo "   - Arquivos: 644 (rw-r--r--)"
echo "   - Storage/Cache: 775 (rwxrwxr-x)"
echo "   - .env: 640 (rw-r-----)"

# Verificar permissões críticas
echo "🔍 Verificando permissões críticas..."
ls -la $PROJECT_DIR/storage/
ls -la $PROJECT_DIR/bootstrap/cache/
ls -la $PROJECT_DIR/.env
