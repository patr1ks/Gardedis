<template>
    <AdminLayout>
        <UserList :users = "users"></UserList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    users:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import UserList from '../User/UserList.vue';


</script>