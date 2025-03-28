<template>
    <AdminLayout>
        <FormList :forms = "forms"></FormList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import FormList from '../Form/FormList.vue';

</script>