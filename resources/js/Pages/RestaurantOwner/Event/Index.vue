<template>
    <RestaurantLayout>
        <EventInfo :event = "event"></EventInfo>
    </RestaurantLayout>
</template>

<script setup>
defineProps({
    event:Array
})
import RestaurantLayout from '../Components/RestaurantLayout.vue';
import EventInfo from './EventInfo.vue';
</script>