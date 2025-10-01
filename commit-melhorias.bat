@echo off
echo ========================================
echo    COMMIT DAS MELHORIAS DOS CARDS
echo ========================================
echo.

echo Verificando status do Git...
git status

echo.
echo Adicionando arquivos modificados...
git add app/Http/Controllers/ForcingController.php
git add resources/views/forcing/index.blade.php
git add MELHORIAS_CARDS_FORCING.md

echo.
echo Fazendo commit das melhorias...
git commit -m "feat: Melhorar cards de status para mostrar contadores gerais

- Corrigir contagem de cards para mostrar dados gerais ao invés de por página
- Implementar contadores independentes para cada status  
- Adicionar atualização via AJAX dos cards
- Melhorar UX com dados precisos e em tempo real
- Respeitar filtros de unidade (multi-tenant)
- Adicionar logs de debug para monitoramento"

echo.
echo Commit realizado com sucesso!
echo.
echo Para fazer push para o repositório remoto, execute:
echo git push origin main
echo.
pause
