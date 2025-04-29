<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50 px-4">
        <div class="flex items-center space-x-2 mb-8">
            <Link href="/">
                <ApplicationLogo class="w-16 h-16" />
            </Link>
            <h1 class="text-4xl font-bold text-gray-800">Gardēdis</h1>
        </div>

        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
            <slot />
        </div>
    </div>
</template>

