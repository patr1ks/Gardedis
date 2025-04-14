<script setup>
import NoHeroLayout from '../Layouts/NoHeroLayout.vue';

defineProps({
  restaurant: Object
})
</script>

<template>
  <NoHeroLayout>
    <div class="p-4">
    <h1 class="text-2xl font-bold">{{ restaurant.title }}</h1>
    <p class="mt-2 text-gray-600">{{ restaurant.description }}</p>

    <img
      v-if="restaurant.restaurant_images.length > 0"
      :src="`/${restaurant.restaurant_images[0].image}`"
      class="mt-4 w-96"
    />
  </div>
  </NoHeroLayout>
</template>
