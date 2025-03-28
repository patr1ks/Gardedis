<template>
    <AdminLayout>
        <PaymentList :payments = "payments"></PaymentList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import PaymentList from '../Payment/PaymentList.vue';

</script>