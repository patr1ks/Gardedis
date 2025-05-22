<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { Link } from '@inertiajs/vue3'
//restaurant list
defineProps({
    restaurants: Array,
    events: Array
});
</script>

<template>
  <UserLayouts>
    <!-- Shared background wrapper for dark mode consistency -->
    <div class="bg-white dark:bg-gray-900 transition-colors duration-300">
      
      <!-- Restaurant Section -->
      <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Restaurant list</h2>

        <div class="mt-6 flex flex-col gap-y-6">
          <Link
            v-for="restaurant in restaurants"
            :key="restaurant.id"
            :href="route('user.restaurant', restaurant.id)"
            class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
          >
            <img
              v-if="restaurant.restaurant_images.length > 0"
              :src="`/${restaurant.restaurant_images[0].image}`"
              :alt="restaurant.imageAlt"
              class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
            />
            <img
              v-else
              src="https://st4.depositphotos.com/14953852/24787/v/450/depositphotos_247872612-stock-illustration-no-image-available-icon-vector.jpg"
              :alt="restaurant.imageAlt"
              class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
            />
            <div class="flex flex-col justify-between p-4 leading-normal w-full">
              <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ restaurant.title }}
              </h5>
              <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                Categories:
                <span v-if="restaurant.categories?.length">
                  <span v-for="(cat, index) in restaurant.categories" :key="cat.id">
                    {{ cat.name }}<span v-if="index < restaurant.categories.length - 1">, </span>
                  </span>
                </span>
                <span v-else>No categories</span>
              </p>
              <div class="text-lg font-semibold text-gray-900 dark:text-white mt-auto">
                Reservation price: {{ restaurant.price }} €
              </div>
            </div>
          </Link>
        </div>
      </div>

      <!-- Separator -->
      <hr class="my-6 border-gray-200 dark:border-gray-700 sm:mx-auto lg:my-8 w-5/6" />

      <!-- Event Section -->
      <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Event list</h2>

        <div class="mt-6 flex flex-col gap-y-6">
          <Link
            v-for="event in events"
            :key="event.id"
            :href="route('user.event', event.id)"
            class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
          >
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

            <div class="flex flex-col justify-between p-4 leading-normal w-full">
              <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ event.title }}
              </h5>
              <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                Restaurant: {{ event.restaurant.title }}
              </p>
              <div class="text-lg font-semibold text-gray-900 dark:text-white mt-auto">
                Event Date: {{ event.event_date }}
              </div>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </UserLayouts>
</template>