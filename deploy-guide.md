# 🚀 Guia de Deploy - Mantendo Permissões Corretas

## 📋 Checklist de Deploy

### ✅ **Antes do Deploy**
- [ ] Fazer backup do banco de dados
- [ ] Verificar se todas as alterações estão commitadas
- [ ] Testar localmente todas as funcionalidades

### ✅ **Durante o Deploy**

#### **1. Pull das Alterações**
```bash
git pull origin main
```

#### **2. Executar Script de Permissões**

**Para Servidores Linux (Ubuntu/CentOS):**
```bash
chmod +x deploy-permissions.sh
sudo ./deploy-permissions.sh
```

**Para Windows (XAMPP/WAMP):**
```batch
deploy-windows.bat
```

#### **3. Instalar/Atualizar Dependências**
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

#### **4. Executar Migrações (se houver)**
```bash
php artisan migrate --force
```

### ✅ **Após o Deploy**

#### **Verificações Importantes:**
- [ ] Verificar se o site carrega corretamente
- [ ] Testar login/logout
- [ ] Verificar funcionalidades mobile
- [ ] Checar logs de erro: `tail -f storage/logs/laravel.log`

## 🔐 **Permissões Críticas por Ambiente**

### **Linux (Produção)**
```bash
# Estrutura recomendada:
/var/www/html/forcing-projeto/
├── storage/           (775 - rwxrwxr-x)
├── bootstrap/cache/   (775 - rwxrwxr-x)
├── public/           (755 - rwxr-xr-x)
├── .env              (640 - rw-r-----)
└── outros arquivos   (644 - rw-r--r--)
```

### **Windows (Desenvolvimento)**
```
C:\xampp\htdocs\forcing-projeto\
├── storage\          (Permissão total para usuário)
├── bootstrap\cache\  (Permissão total para usuário)
├── public\build\     (Permissão total para usuário)
└── outros arquivos   (Padrão do Windows)
```

## ⚠️ **Problemas Comuns e Soluções**

### **Erro: "Permission denied" em storage/**
```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### **Erro: "Class not found" após deploy**
```bash
composer dump-autoload --optimize
php artisan clear-compiled
php artisan cache:clear
```

### **Assets não carregando**
```bash
npm run build
php artisan view:clear
```

### **Sessões não funcionando**
```bash
sudo chmod -R 775 storage/framework/sessions/
php artisan session:table  # Se usando database sessions
```

## 🔄 **Script Automático de Deploy**

Para automatizar todo o processo, você pode criar um script único:

```bash
#!/bin/bash
# deploy-complete.sh

echo "🚀 Iniciando deploy completo..."

# 1. Pull das alterações
git pull origin main

# 2. Instalar dependências
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Configurar permissões
./deploy-permissions.sh

# 4. Limpar e recriar caches
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deploy concluído com sucesso!"
```

## 📱 **Específico para as Melhorias Mobile**

As alterações que fizemos são especialmente sensíveis a:

1. **Cache de assets** (`public/build/`)
2. **Sessões** (`storage/framework/sessions/`)
3. **Configurações** (`config/session.php`)

Certifique-se de que estes diretórios/arquivos tenham as permissões corretas após o deploy.

## 🆘 **Em Caso de Emergência**

Se algo der errado após o deploy:

```bash
# Reverter para commit anterior
git log --oneline -5  # Ver últimos commits
git reset --hard [COMMIT_ANTERIOR]

# Ou fazer rollback específico
git revert [COMMIT_COM_PROBLEMA]
```

## 📞 **Contatos de Suporte**

- **Logs de erro:** `storage/logs/laravel.log`
- **Logs do servidor:** `/var/log/apache2/error.log` ou `/var/log/nginx/error.log`
- **Status do servidor:** `systemctl status apache2` ou `systemctl status nginx`
