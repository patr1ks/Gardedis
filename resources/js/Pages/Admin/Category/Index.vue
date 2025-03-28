<template>
    <AdminLayout>
        <CategoryList :categories = "categories"></CategoryList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import CategoryList from '../Category/CategoryList.vue';

</script>