<template>
    <ProductForm :is-edit="true" :product="product" />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ProductForm from './Form.vue';

const props = defineProps({
    id: String
});

const product = ref(null);

onMounted(async () => {
    try {
        const response = await fetch(`/api/products/${props.id}`);
        if (response.ok) {
            const data = await response.json();
            product.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching product:', error);
    }
});
</script>
