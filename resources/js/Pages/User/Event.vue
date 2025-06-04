<script setup>
import NoHeroLayout from './Layouts/NoHeroLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  events: Array
});
</script>

<template>
  <NoHeroLayout>
    <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Event list</h2>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <Link
            v-for="event in events"
            :key="event.id"
            :href="route('user.event', event.id)"
            class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors"
          >
            <!-- Show event image or fallback -->
            <img
              v-if="event.event_images.length > 0"
              :src="`/${event.event_images[0].image}`"
              alt="Event image"
              class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
            />
            <img
              v-else
              src="https://st4.depositphotos.com/14953852/24787/v/450/depositphotos_247872612-stock-illustration-no-image-available-icon-vector.jpg"
              alt="No image"
              class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
            />

            <!-- Event details -->
            <div class="flex flex-col justify-between p-4 leading-normal w-full">
              <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ event.title }}
              </h5>
              <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                Restaurant: {{ event.restaurant?.title || 'N/A' }}
              </p>
              <div class="text-lg font-semibold text-gray-900 dark:text-white mt-auto">
                Event Date: {{new Date(event.event_date).toLocaleDateString('lv-LV', {day: '2-digit',month: '2-digit',year: 'numeric'})}}
              </div>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </NoHeroLayout>
</template>