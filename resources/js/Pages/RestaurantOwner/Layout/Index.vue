<template>
    <RestaurantLayout>
        <Layout :menu = "menu"></Layout>
    </RestaurantLayout>
</template>

<script setup>
defineProps({
    menus:Array
})
import RestaurantLayout from '../Components/RestaurantLayout.vue';
import Layout from '../Layout/Layout.vue';
</script>