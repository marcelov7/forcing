# 📋 Lógica de Liberação das Alterações de Lógica

**Data:** 01/10/2025  
**Sistema:** Controle de Forcing - Módulo Logic Changes  
**Status:** ✅ ANÁLISE COMPLETA

## 🎯 **Resumo da Lógica Atual**

O sistema possui um **fluxo de aprovação hierárquico** com 3 níveis de aprovação para alterações de lógica:

1. **🏢 Gerente de Manutenção**
2. **👥 Coordenador de Manutenção** 
3. **🔧 Técnico Especialista**

## 👤 **Perfis e Permissões**

### **🔐 Perfis do Sistema:**
```php
- super_admin  → Pode tudo (sem restrição de unidade)
- admin       → Administrador da unidade
- liberador   → Liberador da unidade  
- executante  → Técnico/Executante da unidade
- user        → Usuário comum da unidade
```

## ⚖️ **Matriz de Permissões por Aprovação**

### **1. 🏢 Aprovar como Gerente de Manutenção**
**Método:** `approveAsManager()`

✅ **Quem pode aprovar:**
- ✅ `super_admin` - Pode aprovar como qualquer perfil
- ✅ `admin` - Pode aprovar como gerente (da sua unidade)
- ✅ `liberador` - Pode aprovar como gerente (da sua unidade)
- ❌ `executante` - NÃO pode aprovar como gerente
- ❌ `user` - NÃO pode aprovar como gerente

### **2. 👥 Aprovar como Coordenador de Manutenção**
**Método:** `approveAsCoordinator()`

✅ **Quem pode aprovar:**
- ✅ `super_admin` - Pode aprovar como qualquer perfil
- ✅ `admin` - Pode aprovar como coordenador (da sua unidade)
- ✅ `liberador` - Pode aprovar como coordenador (da sua unidade)
- ✅ `executante` - Pode aprovar como coordenador (da sua unidade) ⚡
- ❌ `user` - NÃO pode aprovar como coordenador

### **3. 🔧 Aprovar como Técnico Especialista**
**Método:** `approveAsSpecialist()`

✅ **Quem pode aprovar:**
- ✅ `super_admin` - Pode aprovar como qualquer perfil
- ✅ `admin` - Pode aprovar como especialista (da sua unidade)
- ✅ `liberador` - Pode aprovar como especialista (da sua unidade)
- ✅ `executante` - Pode aprovar como especialista (da sua unidade) ⚡
- ❌ `user` - NÃO pode aprovar como especialista

## 🏭 **Sistema Multi-Tenant**

### **Restrições por Unidade:**
```php
// MULTI-TENANT: Verificar se pertencem à mesma unidade
if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
    return false;
}
```

✅ **Regras:**
- Usuário só pode aprovar alterações **da sua própria unidade**
- `super_admin` não tem restrição de unidade
- Se alteração não tem `unit_id`, qualquer usuário pode aprovar
- Se usuário não tem `unit_id`, só pode ver/aprovar suas próprias solicitações

## 📊 **Fluxo de Aprovação**

### **Status da Alteração:**
```php
const STATUS_PENDENTE = 'pendente';
const STATUS_EM_ANALISE = 'em_analise';
const STATUS_APROVADO = 'aprovado';
const STATUS_REJEITADO = 'rejeitado';
const STATUS_EM_EXECUCAO = 'em_execucao';
const STATUS_IMPLEMENTADO = 'implementado';
const STATUS_CONCLUIDO = 'concluido';
const STATUS_CANCELADO = 'cancelado';
```

### **Sequência de Aprovações:**
1. **Solicitação criada** → `pendente`
2. **Gerente aprova** → `aprovacao_gerente_manutencao` = now()
3. **Coordenador aprova** → `aprovacao_coordenador_manutencao` = now()
4. **Especialista aprova** → `aprovacao_tecnico_especialista` = now()
5. **Status atualizado** automaticamente após aprovações

## 🔧 **Implementação (Marcar como Implementado)**

### **Método:** `markAsImplemented()`

✅ **Quem pode marcar como implementado:**
- ✅ `super_admin` - Pode marcar como implementado
- ✅ `admin` - Pode marcar como implementado (da sua unidade)
- ✅ `liberador` - Pode marcar como implementado (da sua unidade)
- ✅ `executante` - Pode marcar como implementado (da sua unidade)
- ❌ `user` - NÃO pode marcar como implementado

## 🎛️ **Código Atual das Policies**

### **Policy: `LogicChangePolicy.php`**

#### **Gerente de Manutenção:**
```php
public function approveAsManager(User $user, LogicChange $logicChange): bool
{
    if ($user->isSuperAdmin()) return true;
    
    // Multi-tenant check
    if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
        return false;
    }
    
    if ($user->isAdmin()) return true;
    if ($user->isLiberador()) return true;
    
    return false;
}
```

#### **Coordenador de Manutenção:**
```php
public function approveAsCoordinator(User $user, LogicChange $logicChange): bool
{
    if ($user->isSuperAdmin()) return true;
    
    // Multi-tenant check
    if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
        return false;
    }
    
    if ($user->isAdmin()) return true;
    if ($user->isLiberador()) return true;
    if ($user->isExecutante()) return true;  // ← TÉCNICOS PODEM
    
    return false;
}
```

#### **Técnico Especialista:**
```php
public function approveAsSpecialist(User $user, LogicChange $logicChange): bool
{
    if ($user->isSuperAdmin()) return true;
    
    // Multi-tenant check
    if ($logicChange->unit_id && $user->unit_id !== $logicChange->unit_id) {
        return false;
    }
    
    if ($user->isAdmin()) return true;
    if ($user->isLiberador()) return true;
    if ($user->isExecutante()) return true;  // ← TÉCNICOS PODEM
    
    return false;
}
```

## 📝 **Resumo das Permissões**

| Perfil | Gerente | Coordenador | Especialista | Implementar |
|--------|---------|-------------|--------------|-------------|
| `super_admin` | ✅ | ✅ | ✅ | ✅ |
| `admin` | ✅ | ✅ | ✅ | ✅ |
| `liberador` | ✅ | ✅ | ✅ | ✅ |
| `executante` | ❌ | ✅ | ✅ | ✅ |
| `user` | ❌ | ❌ | ❌ | ❌ |

## 🚨 **Pontos Importantes**

1. **🏢 Gerente de Manutenção:** Apenas `admin` e `liberador` podem aprovar
2. **👥 Coordenador:** `admin`, `liberador` E `executante` podem aprovar
3. **🔧 Especialista:** `admin`, `liberador` E `executante` podem aprovar
4. **📍 Multi-tenant:** Sempre respeitado (exceto super_admin)
5. **⚡ Executantes:** Podem aprovar como Coordenador e Especialista, mas NÃO como Gerente

---

**A lógica atual permite que técnicos (`executante`) aprovem como Coordenador e Especialista, mas não como Gerente de Manutenção! ✅**