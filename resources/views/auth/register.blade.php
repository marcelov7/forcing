@extends('layouts.app')

@section('title', 'Registro - Sistema de Controle de Forcing')

@section('content')
<div class="login-container">
    <div class="row g-0 min-vh-100">
        <!-- Seção de Registro -->
        <div class="col-lg-7 col-md-8 d-flex align-items-center justify-content-center bg-white">
            <div class="register-form-container w-100 px-4">
                <div class="text-center mb-4">
                    <div class="login-logo mb-3" style="margin-top: 20px;">
                        <i class="fas fa-user-plus fa-3x text-success"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Criar Nova Conta</h2>
                    <p class="text-muted">Junte-se ao Sistema de Forcing</p>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="fas fa-user me-2 text-success"></i>Nome Completo
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="Digite seu nome completo" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-semibold">
                                            <i class="fas fa-at me-2 text-success"></i>Usuário
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                               id="username" name="username" value="{{ old('username') }}" 
                                               placeholder="Escolha um nome de usuário" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-success"></i>E-mail
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="seu.email@empresa.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresa" class="form-label fw-semibold">
                                            <i class="fas fa-building me-2 text-success"></i>Empresa
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('empresa') is-invalid @enderror" 
                                               id="empresa" name="empresa" value="{{ old('empresa') }}" 
                                               placeholder="Nome da empresa" required>
                                        @error('empresa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="setor" class="form-label fw-semibold">
                                            <i class="fas fa-users me-2 text-success"></i>Setor
                                        </label>
                                        <input type="text" class="form-control form-control-lg @error('setor') is-invalid @enderror" 
                                               id="setor" name="setor" value="{{ old('setor') }}" 
                                               placeholder="Seu departamento" required>
                                        @error('setor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="fas fa-lock me-2 text-success"></i>Senha
                                        </label>
                                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Crie uma senha segura" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            <i class="fas fa-check me-2 text-success"></i>Confirmar Senha
                                        </label>
                                        <input type="password" class="form-control form-control-lg" 
                                               id="password_confirmation" name="password_confirmation" 
                                               placeholder="Repita a senha" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success btn-lg py-3">
                                    <i class="fas fa-user-plus me-2"></i>Criar Minha Conta
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <p class="mb-0 text-muted">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-success fw-semibold text-decoration-none">
                                Entre aqui
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção da Galeria de Ferramentas (Compacta) -->
        <div class="col-lg-5 col-md-4 bg-gradient-success d-flex align-items-center">
            <div class="tools-gallery-compact w-100 p-4">
                <div class="text-center mb-4">
                    <h4 class="text-white fw-bold mb-2">Bem-vindo ao DevAxis</h4>
                    <p class="text-white-50">Ferramentas integradas para sua empresa</p>
                </div>

                <div class="row g-3">
                    <!-- Sistema de Forcing - Atual -->
                    <div class="col-12">
                        <div class="tool-card-compact active">
                            <div class="tool-icon-compact">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="tool-content-compact">
                                <h6>Sistema de Forcing</h6>
                                <small>Operações críticas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Sistema de Relatórios -->
                    <div class="col-12">
                        <a href="https://app.devaxis.com.br" target="_blank" class="text-decoration-none">
                            <div class="tool-card-compact">
                                <div class="tool-icon-compact">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="tool-content-compact">
                                    <h6>Sistema de Relatórios</h6>
                                    <small>Dashboards e análises</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <i class="fas fa-shield-alt me-2"></i>
                        Seguro e confiável
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.register-form-container {
    max-width: 650px;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

.tools-gallery-compact {
    max-width: 300px;
    margin: 0 auto;
}

.tool-card-compact {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
}

.tool-card-compact:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.tool-card-compact.active {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.4);
}

.tool-icon-compact {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tool-icon-compact i {
    font-size: 18px;
    color: white;
}

.tool-content-compact {
    flex: 1;
    color: white;
}

.tool-content-compact h6 {
    margin-bottom: 2px;
    font-weight: 600;
    font-size: 14px;
}

.tool-content-compact small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
}

/* Mobile Responsiveness para Register */
@media (max-width: 768px) {
    .login-container .row {
        flex-direction: column-reverse;
    }
    
    .tools-gallery-compact {
        padding: 1.5rem 1rem !important;
    }
    
    .tool-card-compact {
        padding: 12px;
    }
    
    .tool-icon-compact {
        width: 35px;
        height: 35px;
    }
    
    .tool-icon-compact i {
        font-size: 16px;
    }
}

/* Dark mode support para Register */
@media (prefers-color-scheme: dark) {
    .bg-white {
        background-color: #1a1a1a !important;
    }
    
    .text-dark {
        color: #ffffff !important;
    }
    
    .text-muted {
        color: #b0b0b0 !important;
    }
    
    .card {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }
    
    .card-footer {
        background-color: #404040 !important;
    }
    
    .card-footer .text-muted {
        color: #b0b0b0 !important;
    }
    
    .form-label {
        color: #ffffff !important;
    }
    
    .form-control {
        background-color: #404040 !important;
        border-color: #555555 !important;
        color: #ffffff !important;
    }
    
    .form-control:focus {
        background-color: #4a4a4a !important;
        border-color: #28a745 !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    }
    
    .form-control::placeholder {
        color: #888888 !important;
    }
    
    /* Links no dark mode */
    .card-footer a {
        color: #28a745 !important;
    }
    
    .card-footer a:hover {
        color: #218838 !important;
    }
}
</style>
@endsection
