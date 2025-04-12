<template>
    <RestaurantLayout>
        <MenuList :menu = "menu"></MenuList>
    </RestaurantLayout>
</template>

<script setup>
defineProps({
    menus:Array
})
import RestaurantLayout from '../Components/RestaurantLayout.vue';
import MenuList from '../Menu/MenuList.vue';
</script>