<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Link
                href="/admin/products"
                class="text-blue-600 hover:underline mb-6 inline-block"
            >
                ← Назад к списку
            </Link >

            <h1 class="text-3xl font-bold text-gray-900 mb-8">
                {{ isEdit ? 'Редактирование товара' : 'Создание нового товара' }}
            </h1>

            <form @submit.prevent="submit" class="bg-white shadow-lg rounded-lg p-8">
                <!-- Сообщения об ошибках -->
                <div v-if="errors.length" class="mb-6 p-4 bg-red-50 border border-red-200 rounded">
                    <ul class="text-red-600">
                        <li v-for="error in errors" :key="error" class="list-disc ml-4">{{ error }}</li>
                    </ul>
                </div>

                <!-- Сообщения об успехе -->
                <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded text-green-600">
                    {{ success }}
                </div>

                <!-- Поля формы -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Название товара *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Введите название товара"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Категория *
                        </label>
                        <select
                            v-model="form.category_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Выберите категорию</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Цена ($) *
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0.01"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Описание
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Введите описание товара"
                        ></textarea>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="loading">
                                {{ isEdit ? 'Сохранение...' : 'Создание...' }}
                            </span>
                            <span v-else>
                                {{ isEdit ? 'Сохранить изменения' : 'Создать товар' }}
                            </span>
                        </button>

                        <button
                            type="button"
                            @click="$inertia.visit('/admin/products')"
                            class="ml-4 px-6 py-3 border border-gray-300 text-gray-700 rounded hover:bg-gray-50"
                        >
                            Отмена
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    product: Object,
    isEdit: Boolean
});

const form = reactive({
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price || '',
    category_id: props.product?.category_id || ''
});

const categories = ref([]);
const errors = ref([]);
const success = ref('');
const loading = ref(false);

const fetchCategories = async () => {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();
        categories.value = data.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const submit = async () => {
    errors.value = [];
    loading.value = true;
    success.value = '';

    try {
        const token = localStorage.getItem('token');
        const url = props.isEdit
            ? `/api/products/${props.product.id}`
            : '/api/products';

        const method = props.isEdit ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(form)
        });

        const data = await response.json();

        if (response.ok) {
            success.value = props.isEdit
                ? 'Товар успешно обновлен!'
                : 'Товар успешно создан!';

            // Через 2 секунды перенаправляем на список товаров
            setTimeout(() => {
                router.visit('/admin/products');
            }, 2000);
        } else {
            if (data.errors) {
                // Обработка ошибок валидации Laravel
                errors.value = Object.values(data.errors).flat();
            } else {
                errors.value = [data.message || 'Произошла ошибка'];
            }
        }
    } catch (error) {
        errors.value = ['Ошибка подключения к серверу'];
    } finally {
        loading.value = false;
    }
};

onMounted(fetchCategories);
</script>
