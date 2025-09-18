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
                    <h3 class="text-white fw-bold mb-3">
                        <i class="fas fa-industry me-2"></i>Automação Industrial
                    </h3>
                    <p class="text-white-50 fs-5">Controle e proteção de sistemas elétricos</p>
                </div>

                <div class="row g-4">
                    <!-- Sistema de Forcing - Atual -->
                    <div class="col-12">
                        <div class="tool-card active electrical-active">
                            <div class="tool-icon electrical-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="tool-content">
                                <h5>Sistema de Forcing</h5>
                                <p class="mb-0">Controle de proteções elétricas</p>
                                <span class="badge electrical-badge-active ms-2">
                                    <i class="fas fa-power-off me-1"></i>ATIVO
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Sistema de Relatórios -->
                    <div class="col-12">
                        <a href="https://app.devaxis.com.br" target="_blank" class="text-decoration-none">
                            <div class="tool-card electrical-secondary">
                                <div class="tool-icon electrical-icon-secondary">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="tool-content">
                                    <h5>Sistema de Relatórios</h5>
                                    <p class="mb-0">Análises de performance elétrica</p>
                                    <span class="external-link electrical-link">
                                        <i class="fas fa-external-link-alt ms-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Automação Industrial -->
                    <div class="col-12">
                        <div class="tool-card coming-soon electrical-coming">
                            <div class="tool-icon electrical-icon-warning">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div class="tool-content">
                                <h5>Automação Industrial</h5>
                                <p class="mb-0">Controladores PLC e SCADA</p>
                                <span class="badge electrical-badge-warning ms-2">
                                    <i class="fas fa-wrench me-1"></i>EM DESENVOLVIMENTO
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="text-white-50 small">
                        <i class="fas fa-shield-alt me-2"></i>
                        Sistemas certificados IEC 61850
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
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #1a365d 100%);
    position: relative;
    overflow: hidden;
}

.login-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(0, 212, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(0, 255, 136, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 215, 0, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #1a365d 0%, #2c5282 50%, #00D4FF 100%) !important;
    position: relative;
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
    background: linear-gradient(135deg, #00D4FF 0%, #0EA5E9 50%, #0284C7 100%);
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 212, 255, 0.5);
    background: linear-gradient(135deg, #0EA5E9 0%, #0284C7 50%, #0369A1 100%);
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

/* Tema Elétrico/Industrial */
.electrical-active {
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.2) 0%, rgba(14, 165, 233, 0.15) 100%);
    border: 1px solid rgba(0, 212, 255, 0.4);
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
}

.electrical-secondary {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.15) 0%, rgba(34, 197, 94, 0.1) 100%);
    border: 1px solid rgba(0, 255, 136, 0.3);
}

.electrical-coming {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(245, 158, 11, 0.08) 100%);
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.electrical-icon {
    background: linear-gradient(135deg, rgba(0, 212, 255, 0.3) 0%, rgba(14, 165, 233, 0.2) 100%);
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
}

.electrical-icon-secondary {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.3) 0%, rgba(34, 197, 94, 0.2) 100%);
    box-shadow: 0 0 15px rgba(0, 255, 136, 0.3);
}

.electrical-icon-warning {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.3) 0%, rgba(245, 158, 11, 0.2) 100%);
    box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
}

.electrical-badge-active {
    background: linear-gradient(135deg, #00FF88 0%, #22C55E 100%);
    color: #000;
    font-weight: 600;
    font-size: 10px;
    letter-spacing: 0.5px;
}

.electrical-badge-warning {
    background: linear-gradient(135deg, #FFD700 0%, #F59E0B 100%);
    color: #000;
    font-weight: 600;
    font-size: 10px;
    letter-spacing: 0.5px;
}

.electrical-link {
    color: rgba(0, 255, 136, 0.8);
}

/* Efeitos de hover para tema elétrico */
.electrical-active:hover {
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.4);
    transform: translateY(-5px);
}

.electrical-secondary:hover {
    box-shadow: 0 0 25px rgba(0, 255, 136, 0.4);
}

.electrical-coming:hover {
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
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
