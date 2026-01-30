<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Каталог товаров</h1>

            <!-- Фильтр по категориям -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Фильтр по категории:</label>
                <select
                    v-model="selectedCategory"
                    @change="fetchProducts(1)"
                    class="border rounded px-3 py-2 w-full md:w-64"
                >
                    <option value="">Все категории</option>
                    <option v-for="category in categories" :value="category.id" :key="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Состояние загрузки -->
            <div v-if="loading" class="text-center py-12">
                <p class="text-gray-500">Загрузка товаров...</p>
            </div>

            <!-- Список товаров -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    v-for="product in products.data"
                    :key="product.id"
                    class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow"
                >
                    <h3 class="text-xl font-semibold text-gray-900">{{ product.name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ product.category?.name }}</p>
                    <p class="text-lg font-bold text-gray-900 mt-2">${{ product.price }}</p>
                    <p class="text-gray-700 mt-3 text-sm line-clamp-3">{{ product.description }}</p>
                    <!-- ИСПРАВЛЕНО: inertia-link → Link -->
                    <Link
                        :href="`/products/${product.id}`"
                        class="inline-block mt-4 text-blue-600 hover:underline"
                    >
                        Подробнее
                    </Link>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="!loading && products.data && products.data.length > 0" class="mt-8">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                        Показано {{ products.data.length }} из {{ products.meta?.total || 0 }} товаров
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Кнопка "Назад" -->
                        <button
                            :disabled="!products.links?.prev"
                            @click="fetchProducts(products.meta.current_page - 1)"
                            :class="[
                                'px-4 py-2 border rounded',
                                products.links?.prev
                                    ? 'hover:bg-gray-50 cursor-pointer'
                                    : 'opacity-50 cursor-not-allowed'
                            ]"
                        >
                            Назад
                        </button>

                        <!-- Номера страниц -->
                        <div class="flex space-x-1">
                            <button
                                v-for="page in pagesToShow"
                                :key="page"
                                @click="fetchProducts(page)"
                                :class="[
                                    'px-3 py-1 border rounded',
                                    page === products.meta?.current_page
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'hover:bg-gray-50'
                                ]"
                            >
                                {{ page }}
                            </button>
                        </div>

                        <!-- Кнопка "Вперед" -->
                        <button
                            :disabled="!products.links?.next"
                            @click="fetchProducts(products.meta.current_page + 1)"
                            :class="[
                                'px-4 py-2 border rounded',
                                products.links?.next
                                    ? 'hover:bg-gray-50 cursor-pointer'
                                    : 'opacity-50 cursor-not-allowed'
                            ]"
                        >
                            Вперед
                        </button>
                    </div>
                </div>
            </div>

            <!-- Сообщение, если товаров нет -->
            <div v-if="!loading && products.data && products.data.length === 0" class="text-center py-12">
                <p class="text-gray-500">Товары не найдены</p>
                <button @click="resetFilters" class="mt-4 text-blue-600 hover:underline">
                    Сбросить фильтры
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3'; // ИСПРАВЛЕНО: Добавлен импорт Link
import AppLayout from '@/Layouts/AppLayout.vue';

// Реактивные данные
const products = ref({
    data: [],
    links: {},
    meta: {}
});
const categories = ref([]);
const selectedCategory = ref('');
const loading = ref(false);

// Вычисляемые свойства для пагинации
const pagesToShow = computed(() => {
    if (!products.value.meta || !products.value.meta.last_page) return [];

    const current = products.value.meta.current_page;
    const last = products.value.meta.last_page;
    const delta = 2;
    const range = [];

    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }

    if (current - delta > 2) {
        range.unshift('...');
    }
    if (current + delta < last - 1) {
        range.push('...');
    }

    range.unshift(1);
    if (last > 1) range.push(last);

    return range;
});

// Функция загрузки товаров
const fetchProducts = async (page = 1) => {
    loading.value = true;
    try {
        // Формируем URL с параметрами
        const params = new URLSearchParams({
            page: page.toString()
        });

        if (selectedCategory.value) {
            params.append('category_id', selectedCategory.value);
        }

        const response = await fetch(`/api/products?${params}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        products.value = data;
    } catch (error) {
        console.error('Ошибка загрузки товаров:', error);
        alert('Не удалось загрузить товары');
    } finally {
        loading.value = false;
    }
};

// Функция загрузки категорий
const fetchCategories = async () => {
    try {
        const response = await fetch('/api/categories');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        categories.value = data.data;
    } catch (error) {
        console.error('Ошибка загрузки категорий:', error);
    }
};

// Сброс фильтров
const resetFilters = () => {
    selectedCategory.value = '';
    fetchProducts(1);
};

// Инициализация при загрузке
onMounted(() => {
    fetchProducts();
    fetchCategories();
});
</script>
