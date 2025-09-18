<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Controle de Forcing')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS customizado para mobile e desktop -->
    <style>
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            font-weight: 500;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
        }
        
        /* Melhorias específicas para mobile */
        .mobile-device {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        .mobile-device input, 
        .mobile-device textarea, 
        .mobile-device select {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
        
        /* Melhorar botões em touch devices */
        @media (max-width: 768px) {
            .btn {
                min-height: 44px;
                padding: 12px 16px;
            }
            
            .btn-sm {
                min-height: 36px;
                padding: 8px 12px;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .navbar-toggler {
                padding: 8px 12px;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }
            
            /* Melhorar formulários em mobile */
            .form-control, .form-select {
                min-height: 44px;
                font-size: 16px; /* Previne zoom no iOS */
            }
            
            /* Alertas mais visíveis em mobile */
            .alert {
                margin-bottom: 1rem;
                border-radius: 8px;
            }
        }
        
        /* Loading indicator para requests AJAX */
        .loading {
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('forcing.index') }}">
                <i class="fas fa-exclamation-triangle"></i> Controle de Forcing
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('forcing.*') ? 'active' : '' }}" href="{{ route('forcing.index') }}">
                                <i class="fas fa-list"></i> Forcing
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('logic-changes.*') ? 'active' : '' }}" href="{{ route('logic-changes.index') }}">
                                <i class="fas fa-cogs"></i> Alterações de Lógica
                            </a>
                        </li>
                        
                        @if(auth()->user()->perfil === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users"></i> Usuários
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->is_super_admin)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.units.index') }}">
                                    <i class="fas fa-building"></i> Unidades
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }}
                                <span class="badge bg-secondary">{{ ucfirst(auth()->user()->perfil) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i> Meu Perfil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-edit"></i> Editar Perfil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Entrar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Registrar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6><i class="fas fa-exclamation-triangle"></i> Erro(s) encontrado(s):</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Sistema de Controle de Forcing. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript para melhorias em mobile -->
    <script>
        // Detectar se é mobile
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            // Melhorar performance em mobile
            document.addEventListener('DOMContentLoaded', function() {
                // Adicionar classe para identificação CSS
                document.body.classList.add('mobile-device');
                
                // Otimizar touch events
                document.body.style.touchAction = 'manipulation';
                
                // Prevenir zoom duplo toque em inputs
                const inputs = document.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('touchstart', function() {
                        this.style.fontSize = '16px';
                    });
                });
                
                // Auto-refresh de sessão para evitar timeouts em mobile
                let sessionRefreshInterval = setInterval(function() {
                    fetch('/csrf-token-refresh', {
                        method: 'GET',
                        credentials: 'same-origin'
                    }).then(response => {
                        if (!response.ok) {
                            clearInterval(sessionRefreshInterval);
                            console.log('Sessão expirada, redirecionando para login...');
                            window.location.href = '/login';
                        }
                        return response.json();
                    }).then(data => {
                        if (data.token) {
                            // Atualizar token CSRF
                            const token = document.head.querySelector('meta[name="csrf-token"]');
                            if (token) {
                                token.setAttribute('content', data.token);
                            }
                            // Atualizar tokens em formulários
                            const csrfInputs = document.querySelectorAll('input[name="_token"]');
                            csrfInputs.forEach(input => {
                                input.value = data.token;
                            });
                        }
                    }).catch(error => {
                        console.log('Erro ao renovar sessão:', error);
                    });
                }, 300000); // A cada 5 minutos
                
                // Limpar interval quando sair da página
                window.addEventListener('beforeunload', function() {
                    clearInterval(sessionRefreshInterval);
                });
            });
            
            // Melhorar navegação em mobile
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    // Página foi carregada do cache - verificar se ainda está autenticado
                    fetch('/csrf-token-refresh', {
                        method: 'GET',
                        credentials: 'same-origin'
                    }).catch(() => {
                        window.location.reload();
                    });
                }
            });
        }
        
        // Função global para lidar com erros de formulário
        window.handleFormError = function(error) {
            if (error.status === 419) {
                // Token CSRF expirado
                alert('Sua sessão expirou. A página será recarregada.');
                window.location.reload();
            } else if (error.status === 401) {
                // Não autorizado
                alert('Você precisa fazer login novamente.');
                window.location.href = '/login';
            }
        };
    </script>
    
    @yield('scripts')
</body>
</html>
