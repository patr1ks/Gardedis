<script setup>
import NoHeroLayout from '../Layouts/NoHeroLayout.vue';

defineProps({
  event: Object
})
</script>

<template>
  <NoHeroLayout>
    <div class="p-4">
        <h1 class="text-2xl font-bold">{{ event.title }}</h1>

        <p class="mt-2 text-gray-600">
        Event Date: {{ event.event_date }}
        </p>

        <p class="mt-2 text-gray-600">
        Restaurant: {{ event.restaurant?.title || 'N/A' }}
        </p>

        <img
        v-if="event.event_images.length > 0"
        :src="`/${event.event_images[0].image}`"
        class="mt-4 w-96"
        />
    </div>
  </NoHeroLayout>
</template>
