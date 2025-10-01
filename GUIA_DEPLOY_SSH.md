# 🚀 Guia de Deploy via SSH - Sistema Forcing

## 📋 Informações do Servidor
- **IP**: 31.97.168.137
- **Usuário**: root
- **Domínio**: forcing.devaxis.com.br
- **Diretório**: /home/devaxis-forcing/htdocs/forcing.devaxis.com.br

## 🔧 Passos para Deploy

### 1. **Commit Local (se necessário)**
```bash
# Adicionar arquivos modificados
git add app/Http/Controllers/ForcingController.php
git add resources/views/forcing/index.blade.php
git add MELHORIAS_CARDS_FORCING.md

# Fazer commit
git commit -m "feat: Melhorar cards de status para mostrar contadores gerais

- Corrigir contagem de cards para mostrar dados gerais ao invés de por página
- Implementar contadores independentes para cada status
- Adicionar atualização via AJAX dos cards
- Melhorar UX com dados precisos e em tempo real
- Respeitar filtros de unidade (multi-tenant)"

# Fazer push para o repositório
git push origin main
```

### 2. **Conectar via SSH**
```bash
ssh root@31.97.168.137
```

### 3. **Comandos no Servidor**
```bash
# Navegar para o diretório do projeto
cd /home/devaxis-forcing/htdocs/forcing.devaxis.com.br

# Fazer backup da versão atual
cp -r . ../backup-$(date +%Y%m%d-%H%M%S)/

# Atualizar código do Git
git pull origin main

# Instalar dependências
composer install --no-dev --optimize-autoloader

# Limpar e recriar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Definir permissões corretas
chown -R devaxis-forcing:devaxis-forcing .
chmod -R 755 storage bootstrap/cache
```

### 4. **Verificar Deploy**
- Acesse: https://forcing.devaxis.com.br
- Verifique se os cards mostram contadores corretos
- Teste o botão "Atualizar" para verificar AJAX

## 🛠️ Scripts Disponíveis

### Script Automático (Linux/Mac)
```bash
chmod +x deploy-completo.sh
./deploy-completo.sh
```

### Script Manual (Windows)
```cmd
deploy-ssh.bat
```

## 🔍 Verificações Pós-Deploy

1. **Cards de Status**
   - Verificar se mostram contadores gerais
   - Testar atualização via AJAX

2. **Logs do Sistema**
   - Verificar logs em: `storage/logs/laravel.log`
   - Procurar por "Contadores gerais calculados"

3. **Performance**
   - Verificar se não há erros 500
   - Testar funcionalidades principais

## 🚨 Troubleshooting

### Se houver problemas:
1. **Reverter para backup**:
   ```bash
   cd /home/devaxis-forcing/htdocs/
   rm -rf forcing.devaxis.com.br
   mv backup-YYYYMMDD-HHMMSS forcing.devaxis.com.br
   ```

2. **Verificar permissões**:
   ```bash
   chown -R devaxis-forcing:devaxis-forcing /home/devaxis-forcing/htdocs/forcing.devaxis.com.br
   chmod -R 755 /home/devaxis-forcing/htdocs/forcing.devaxis.com.br/storage
   ```

3. **Limpar caches**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## ✅ Checklist de Deploy

- [ ] Commit local realizado
- [ ] Push para repositório
- [ ] Conexão SSH estabelecida
- [ ] Backup criado
- [ ] Código atualizado
- [ ] Dependências instaladas
- [ ] Caches otimizados
- [ ] Permissões definidas
- [ ] Teste no navegador
- [ ] Verificação de logs

## 📞 Suporte

Em caso de problemas, verificar:
1. Logs do Laravel: `storage/logs/laravel.log`
2. Logs do servidor web
3. Permissões de arquivos
4. Configurações do banco de dados
