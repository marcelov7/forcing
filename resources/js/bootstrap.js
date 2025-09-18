import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configuração para mobile - auto-refresh de CSRF token
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token não encontrado: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Interceptador para renovar token CSRF automaticamente em caso de erro 419
window.axios.interceptors.response.use(
    response => response,
    async error => {
        if (error.response?.status === 419) {
            // Token CSRF expirado - tentar renovar
            try {
                await fetch('/csrf-token-refresh', {
                    method: 'GET',
                    credentials: 'same-origin'
                }).then(response => response.json())
                .then(data => {
                    if (data.token) {
                        // Atualizar token no meta tag
                        token.setAttribute('content', data.token);
                        // Atualizar token no axios
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = data.token;
                        // Tentar novamente a requisição original
                        error.config.headers['X-CSRF-TOKEN'] = data.token;
                        return window.axios.request(error.config);
                    }
                });
            } catch (refreshError) {
                console.error('Erro ao renovar CSRF token:', refreshError);
                // Redirecionar para login se não conseguir renovar
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);
