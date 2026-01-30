<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Вход в административную панель
                </h2>
            </div>

            <form @submit.prevent="login" class="mt-8 space-y-6">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Email"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Пароль</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Пароль"
                        />
                    </div>
                </div>

                <div v-if="errors.length" class="text-red-600 text-sm">
                    <p v-for="error in errors" :key="error">{{ error }}</p>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        <span v-if="loading">Вход...</span>
                        <span v-else>Войти</span>
                    </button>
                </div>

                <div class="text-sm text-center">
                    <p class="text-gray-600">Демо доступ:</p>
                    <p class="text-gray-500">admin@example.com / password</p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { csrf } from '@/utils/csrf.js';

const form = reactive({
    email: '',
    password: ''
});

const errors = ref([]);
const loading = ref(false);

const login = async () => {
    errors.value = [];
    loading.value = true;

    try {
        const response = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...csrf.headers()
            },
            body: JSON.stringify(form),
            credentials: 'include'
        });

        const data = await response.json();

        if (response.ok) {
            console.log('Успешный вход:', data);

            // Обновляем CSRF токен на странице
            if (data.csrf_token) {
                csrf.update(data.csrf_token);
            }

            // Сохраняем токен для API запросов (если возвращается)
            if (data.token) {
                localStorage.setItem('token', data.token);
            }

            // Используем редирект из ответа
            window.location.href = data.redirect || '/admin/products';
        } else {
            if (data.errors) {
                errors.value = Object.values(data.errors).flat();
            } else if (data.message) {
                errors.value = [data.message];
            } else {
                errors.value = ['Неверные учетные данные'];
            }
        }
    } catch (error) {
        errors.value = ['Ошибка подключения к серверу'];
        console.error('Login error:', error);
    } finally {
        loading.value = false;
    }
};
</script>
