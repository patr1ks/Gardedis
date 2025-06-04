<script setup>
import NoHeroLayout from '../Layouts/NoHeroLayout.vue';
import { ref } from 'vue';
import { StarIcon } from '@heroicons/vue/20/solid';
import { RadioGroup, RadioGroupOption } from '@headlessui/vue';

// Props
const props = defineProps({
  event: Object
})

// Correct way — no need to write props.restaurant
const currentIndex = ref(0)

const nextImage = () => {
  if (currentIndex.value < props.event.event_images.length - 1) {
    currentIndex.value++
  } else {
    currentIndex.value = 0
  }
}

const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  } else {
    currentIndex.value = props.event.event_images.length - 1
  }
}
</script>


<template>
  <NoHeroLayout>
    <div class="bg-white dark:bg-gray-900">
      <div class="pt-6">

        <!-- Image gallery -->
        <div v-if="event && event.event_images.length > 0" class="relative w-full">
          <!-- Carousel wrapper -->
          <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <div
              v-for="(image, index) in event.event_images"
              :key="index"
              :class="[
                'absolute inset-0 transition-opacity duration-700 ease-in-out',
                index === currentIndex ? 'opacity-100 z-10' : 'opacity-0 z-0'
              ]"
            >
              <img
                :src="`/${image.image}`"
                class="w-full h-full object-contain"
                alt="Event Image"
              />
            </div>
          </div>
          <!-- Controls -->
          <div class="flex justify-center pt-4 space-x-4">
            <button
              @click="prevImage"
              class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-200 hover:bg-blue-600 hover:text-white transition"
              aria-label="Previous"
            >&lt;</button>
            <button
              @click="nextImage"
              class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-200 hover:bg-blue-600 hover:text-white transition"
              aria-label="Next"
            >&gt;</button>
          </div>

        </div>

        <div v-else>
          <p class="text-center text-gray-500 dark:text-gray-400">No images available for this event.</p>
        </div>

        <!-- Product info -->
        <div class="mx-auto max-w-2xl px-4 pt-10 pb-16 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto_auto_1fr] lg:gap-x-8 lg:px-8 lg:pt-16 lg:pb-24">
          <div class="lg:col-span-2 lg:border-r lg:border-gray-200 dark:lg:border-gray-700 lg:pr-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ event.title }}</h1>
          </div>

          <!-- Options -->
          <div class="mt-4 lg:row-span-3 lg:mt-0">
            <p class="text-3xl tracking-tight text-gray-900 dark:text-white">
              Restaurant: {{ event.restaurant?.title}}
            </p>
            <p class="text-2xl tracking-tight text-gray-700 dark:text-gray-300 mt-2">
              Event Date: {{new Date(event.event_date).toLocaleDateString('lv-LV', {day: '2-digit',month: '2-digit',year: 'numeric'})}}
            </p>
          </div>

          <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 dark:lg:border-gray-700 lg:pt-6 lg:pr-8 lg:pb-16">
            <!-- Description and details -->
            <div>
              <h3 class="sr-only">Description</h3>
              <div class="space-y-6">
                <p class="text-base text-gray-900 dark:text-gray-300">{{ event.description }}</p>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </NoHeroLayout>
</template>