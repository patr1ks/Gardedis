<template>
    <AdminLayout>
        <RestaurantList :restaurants = "restaurants"></RestaurantList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import RestaurantList from '../Restaurant/RestaurantList.vue';

</script>