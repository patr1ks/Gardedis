<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
//restaurant list
defineProps({
    restaurants:Array
})
</script>

<template>
    <UserLayouts>
    <!-- main content -->
    <div class="bg-white">
  <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Restaurant list</h2>

    <div class="mt-6 flex flex-col gap-y-6">
      <div
        v-for="restaurant in restaurants"
        :key="restaurant.id"
        class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
      >
        <img
        v-if="restaurant.restaurant_images.length > 0"
          :src="`/${restaurant.restaurant_images[0].image}`"
          :alt="restaurant.imageAlt"
          class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
        />
        <img
        v-else="restaurant.restaurant_images.length > 0"
          src="https://st4.depositphotos.com/14953852/24787/v/450/depositphotos_247872612-stock-illustration-no-image-available-icon-vector.jpg"
          :alt="restaurant.imageAlt"
          class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
        />
        <div class="flex flex-col justify-between p-4 leading-normal w-full">
          <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ restaurant.title }}
          </h5>
          <!-- {{ restaurant.description }} -->
          <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ restaurant.category.name }}</p>
          <div class="text-lg font-semibold text-gray-900 dark:text-white mt-auto">Rezervācijas cena:
            {{ restaurant.price }} €
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- end -->
    </UserLayouts>

     
</template>