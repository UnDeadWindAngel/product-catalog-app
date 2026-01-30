<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Управление товарами</h1>
                <Link
                    href="/admin/products/create"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    + Добавить товар
                </Link>
            </div>

            <!-- Таблица товаров -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Категория</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in products" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ product.description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ product.price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <Link
                                    :href="`/products/${product.id}`"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Просмотр
                                </Link>
                                <Link
                                    :href="`/admin/products/${product.id}/edit`"
                                    class="text-green-600 hover:text-green-900"
                                >
                                    Редактировать
                                </Link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Удалить
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Состояние загрузки -->
                <div v-if="loading" class="text-center py-8">
                    <p class="text-gray-500">Загрузка...</p>
                </div>

                <!-- Состояние пустого списка -->
                <div v-else-if="products.length === 0" class="text-center py-8">
                    <p class="text-gray-500">Товаров пока нет</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const products = ref([]);
const loading = ref(true);

const fetchProducts = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await fetch('/api/products?per_page=50', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            products.value = data.data;
        } else if (response.status === 401) {
            // Если не авторизован, перенаправляем на страницу входа
            window.location.href = '/login';
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

const deleteProduct = async (id) => {
    if (!confirm('Вы уверены, что хотите удалить этот товар?')) {
        return;
    }

    try {
        const token = localStorage.getItem('token');
        const response = await fetch(`/api/products/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            // Удаляем товар из списка
            products.value = products.value.filter(p => p.id !== id);
            alert('Товар успешно удален');
        } else {
            alert('Ошибка при удалении товара');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Ошибка при удалении товара');
    }
};

onMounted(fetchProducts);
</script>
