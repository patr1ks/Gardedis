<template>
    <RestaurantLayout>
        <RestaurantInfo :restaurant = "restaurant"></RestaurantInfo>
    </RestaurantLayout>
</template>

<script setup>
defineProps({
    restaurant:Array
})
import RestaurantLayout from '../Components/RestaurantLayout.vue';
import RestaurantInfo from '../Restaurant/RestaurantInfo.vue';
</script>