<script setup>
import NoHeroLayout from "./Layouts/NoHeroLayout.vue";
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  restaurants: Array,
});


const allCategories = usePage().props.categories
const selectedCategories = ref([])
const filtersVisible = ref(false)

const filteredRestaurants = computed(() => {
  if (selectedCategories.value.length === 0) {
    return props.restaurants
  }

  return props.restaurants.filter(restaurant =>
    restaurant.categories?.some(cat =>
      selectedCategories.value.includes(cat.name)
    )
  )
})

const toggleCategory = (category) => {
  if (selectedCategories.value.includes(category)) {
    selectedCategories.value = selectedCategories.value.filter(c => c !== category)
  } else {
    selectedCategories.value.push(category)
  }
}
</script>

<template>
  <NoHeroLayout>
    <div class="bg-white dark:bg-gray-900 transition-colors duration-300 min-h-screen">
      <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Restaurant list</h2>
          <button
            @click="filtersVisible = !filtersVisible"
            class="p-2 rounded-full bg-blue-600 hover:bg-blue-700 transition"
            aria-label="Toggle filters"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="28"
              height="28"
              viewBox="0 0 1792 1792"
              fill="white"
            >
              <path
                d="M1595 295q17 41-14 70l-493 493v742q0 42-39 59-13 5-25 5-27 0-45-19l-256-256q-19-19-19-45v-486l-493-493q-31-29-14-70 17-39 59-39h1280q42 0 59 39z"
              />
            </svg>
          </button>
        </div>

        <!-- Filters -->
        <div v-if="filtersVisible" class="mb-6 bg-gray-50 dark:bg-gray-800 p-4 rounded shadow">
          <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Filter by Category:</h3>
          <div class="flex flex-wrap gap-4">
            <label
              v-for="category in allCategories"
              :key="category.id"
              class="flex items-center space-x-2 text-gray-800 dark:text-gray-300"
            >
              <input
                type="checkbox"
                :value="category.name"
                v-model="selectedCategories"
                class="accent-blue-600"
              />
              <span>{{ category.name }}</span>
            </label>
          </div>
        </div>

        <!-- Restaurant List -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <Link
            v-for="restaurant in filteredRestaurants"
            :key="restaurant.id"
            :href="route('user.restaurant', restaurant.id)"
            class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors"
          >
            <img
              v-if="restaurant.restaurant_images.length > 0"
              :src="`/${restaurant.restaurant_images[0].image}`"
              :alt="restaurant.imageAlt || 'Restaurant image'"
              class="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
            />
            <img
              v-else
              src="https://st4.depositphotos.com/14953852/24787/v/450/depositphotos_247872612-stock-illustration-no-image-available-icon-vector.jpg"
              :alt="restaurant.imageAlt || 'No image available'"
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
    </div>
  </NoHeroLayout>
</template>