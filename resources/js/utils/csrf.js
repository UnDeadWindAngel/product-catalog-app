// Утилита для работы с CSRF токеном
export const csrf = {
    // Получить текущий токен
    token() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    },

    // Обновить токен на странице
    update(newToken) {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) {
            meta.setAttribute('content', newToken);
        }

        // Также обновляем в скрытом поле, если оно есть
        const input = document.querySelector('input[name="_token"]');
        if (input) {
            input.value = newToken;
        }

        console.log('CSRF token updated');
    },

    // Добавить токен в заголовки fetch запросов
    headers(additionalHeaders = {}) {
        return {
            'X-CSRF-TOKEN': this.token(),
            'X-Requested-With': 'XMLHttpRequest',
            ...additionalHeaders
        };
    }
};

// Автоматически добавляем CSRF токен ко всем fetch запросам
const originalFetch = window.fetch;
window.fetch = async function(resource, options = {}) {
    // Если это запрос к нашему домену и не API запрос
    if (typeof resource === 'string' &&
        resource.startsWith('/') &&
        !resource.startsWith('/api/') &&
        ['POST', 'PUT', 'PATCH', 'DELETE'].includes((options.method || 'GET').toUpperCase())) {

        options.headers = {
            ...csrf.headers(),
            ...options.headers
        };
    }

    return originalFetch(resource, options);
};
