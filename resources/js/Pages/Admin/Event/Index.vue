<template>
    <AdminLayout>
        <EventList :events = "events"></EventList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    restaurants:Array
})
import AdminLayout from '../Components/AdminLayout.vue';
import EventList from '../Event/EventList.vue';


</script>