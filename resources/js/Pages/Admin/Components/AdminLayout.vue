<template>
<div class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Navbar -->
    <Navbar></Navbar>

    <!-- Navbar end-->

    <!-- Sidebar -->
    <Sidebar></Sidebar>

    <!-- Sidebar end-->


    <slot/>

  </div>
</template>

<script setup>
// defineProps({
//     admin:String,
// })

import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from  './NavBar.vue'
import Sidebar from './Sidebar.vue';
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>