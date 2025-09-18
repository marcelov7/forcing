@extends('layouts.app')

@section('title', 'Login - Sistema de Controle de Forcing')

@section('content')
<div class="login-container">
    <div class="row g-0 min-vh-100">
        <!-- Seção de Login -->
        <div class="col-lg-5 col-md-6 d-flex align-items-center justify-content-center bg-white">
            <div class="login-form-container w-100 px-4">
                <div class="text-center mb-4">
                    <div class="login-logo mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-primary"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Sistema de Forcing</h2>
                    <p class="text-muted">Controle e gestão de operações</p>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="fas fa-user me-2 text-primary"></i>Usuário
                                </label>
                                <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="Digite seu usuário" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>Senha
                                </label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Digite sua senha" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg py-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Entrar no Sistema
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <p class="mb-0 text-muted">
                            Não tem uma conta? 
                            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                                Registre-se aqui
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção da Galeria de Ferramentas -->
        <div class="col-lg-7 col-md-6 bg-gradient-primary d-flex align-items-center">
            <div class="tools-gallery w-100 p-5">
                <div class="text-center mb-5">
                    <h3 class="text-white fw-bold mb-3">Ecossistema DevAxis</h3>
                    <p class="text-white-50 fs-5">Acesse nossas ferramentas integradas</p>
                </div>

                <div class="row g-4">
                    <!-- Sistema de Forcing - Atual -->
                    <div class="col-12">
                        <div class="tool-card active">
                            <div class="tool-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="tool-content">
                                <h5>Sistema de Forcing</h5>
                                <p class="mb-0">Controle e gestão de operações críticas</p>
                                <span class="badge bg-success ms-2">Atual</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sistema de Relatórios -->
                    <div class="col-12">
                        <a href="https://app.devaxis.com.br" target="_blank" class="text-decoration-none">
                            <div class="tool-card">
                                <div class="tool-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="tool-content">
                                    <h5>Sistema de Relatórios</h5>
                                    <p class="mb-0">Análises e dashboards completos</p>
                                    <span class="external-link">
                                        <i class="fas fa-external-link-alt ms-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Placeholder para futuras ferramentas -->
                    <div class="col-12">
                        <div class="tool-card coming-soon">
                            <div class="tool-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="tool-content">
                                <h5>Mais Ferramentas</h5>
                                <p class="mb-0">Novas funcionalidades em desenvolvimento</p>
                                <span class="badge bg-warning text-dark ms-2">Em Breve</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="text-white-50 small">
                        <i class="fas fa-shield-alt me-2"></i>
                        Ambiente seguro e confiável
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.login-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.login-form-container {
    max-width: 450px;
}

.login-logo {
    margin-top: 20px; /* Mais espaço do topo */
}

.login-logo i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.tool-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
}

.tool-card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.tool-card.active {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.4);
}

.tool-card.coming-soon {
    opacity: 0.7;
    cursor: not-allowed;
}

.tool-card.coming-soon:hover {
    transform: none;
}

.tool-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tool-icon i {
    font-size: 24px;
    color: white;
}

.tool-content {
    flex: 1;
    color: white;
}

.tool-content h5 {
    margin-bottom: 5px;
    font-weight: 600;
}

.tool-content p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
}

.external-link {
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .login-container .row {
        flex-direction: column-reverse;
    }
    
    .tools-gallery {
        padding: 2rem 1rem !important;
    }
    
    .tool-card {
        padding: 15px;
    }
    
    .tool-icon {
        width: 50px;
        height: 50px;
    }
    
    .tool-icon i {
        font-size: 20px;
    }
}

/* Dark mode support */
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
        border-color: #667eea !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    }
    
    .form-control::placeholder {
        color: #888888 !important;
    }
    
    /* Links no dark mode */
    .card-footer a {
        color: #667eea !important;
    }
    
    .card-footer a:hover {
        color: #5a6fd8 !important;
    }
}
</style>
@endsection
