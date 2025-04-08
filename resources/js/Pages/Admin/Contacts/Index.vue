<template>
    <AdminLayout>
        <ContactsList :contacts = "contacts"></ContactsList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    contacts:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import ContactsList from './ContactsList.vue';

</script>