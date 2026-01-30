<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Link href="/" class="text-blue-600 hover:underline mb-6 inline-block">
                ← Назад к каталогу
            </Link>

            <div v-if="product" class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>
                            <span class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full mt-2">
                                {{ product.category.name }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-gray-900">${{ product.price }}</p>
                            <p class="text-sm text-gray-500 mt-1">ID: {{ product.id }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Описание</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ product.description || 'Описание отсутствует' }}</p>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-semibold">Дата создания:</span>
                                <p>{{ new Date(product.created_at).toLocaleDateString() }}</p>
                            </div>
                            <div>
                                <span class="font-semibold">Последнее обновление:</span>
                                <p>{{ new Date(product.updated_at).toLocaleDateString() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Административные кнопки (только для авторизованных) -->
                <div v-if="$page.props.auth?.user" class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                    <div class="flex space-x-4">
                        <Link
                            :href="`/admin/products/${product.id}/edit`"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Редактировать
                        </Link>
                        <button
                            @click="confirmDelete"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <p class="text-gray-500">Товар не найден</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    id: String
});

const product = ref(null);

const fetchProduct = async () => {
    try {
        const response = await fetch(`/api/products/${props.id}`);
        if (response.ok) {
            const data = await response.json();
            product.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching product:', error);
    }
};

const confirmDelete = () => {
    if (confirm('Вы уверены, что хотите удалить этот товар?')) {
        router.delete(`/api/products/${props.id}`);
    }
};

onMounted(fetchProduct);
</script>
