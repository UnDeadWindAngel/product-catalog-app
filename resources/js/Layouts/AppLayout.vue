<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Навигация -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <Link href="/" class="text-xl font-bold text-gray-800">
                                Product Catalog
                            </Link>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link href="/" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Главная
                            </Link>
                            <Link v-if="$page.props.auth?.user" href="/admin/products" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Управление товарами
                            </Link>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <template v-if="$page.props.auth?.user">
                            <span class="text-gray-700 mr-4">{{ $page.props.auth.user.name }}</span>
                            <button @click="handleLogout" class="text-sm text-gray-500 hover:text-gray-700 focus:outline-none">
                                Выйти
                            </button>
                        </template>
                        <template v-else>
                            <Link href="/login" class="text-sm text-gray-500 hover:text-gray-700">
                                Войти
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Основное содержимое -->
        <main class="py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { csrf } from '@/utils/csrf.js';

// Функция для выхода из системы
const handleLogout = async () => {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...csrf.headers()
            },
            body: JSON.stringify({}),
            credentials: 'include'
        });

        const data = await response.json();

        if (response.ok) {
            console.log('Успешный выход:', data);

            // Обновляем CSRF токен на странице
            if (data.csrf_token) {
                csrf.update(data.csrf_token);
            }

            // Очищаем токен из localStorage
            localStorage.removeItem('token');

            // Перенаправляем на главную
            router.visit(data.redirect || '/', {
                method: 'get',
                preserveScroll: false
            });
        } else {
            console.error('Ошибка выхода:', data);
            alert(data.message || 'Ошибка при выходе из системы');
        }
    } catch (error) {
        console.error('Ошибка сети при выходе:', error);
        alert('Ошибка сети при выходе из системы');
    }
};
</script>
