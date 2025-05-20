<template>
    <RestaurantLayout>
        <PaymentList :payments = "payments"></PaymentList>
    </RestaurantLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import PaymentList from '../Payment/PaymentList.vue';
import RestaurantLayout from '../Components/RestaurantLayout.vue';


</script>