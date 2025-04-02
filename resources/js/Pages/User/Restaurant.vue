<script setup>
import Header from './Layouts/Header.vue';
import Footer from './Layouts/Footer.vue';
//restaurant list
defineProps({
    restaurants:Array
})
</script>

<template>
    <Header></Header>


    <Footer></Footer>
</template>