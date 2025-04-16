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
  <div class="bg-white">
    <div class="pt-6">
      <!-- Image gallery -->
      <div 
    v-if="event && event.event_images.length > 0" 
    class="relative w-full"
  >
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
    <div class="flex justify-center items-center pt-4">
      <button 
        type="button" 
        @click="prevImage"
        class="me-4 h-full cursor-pointer group focus:outline-none"
      >
        <svg class="w-5 h-5 text-gray-400 hover:text-gray-900" fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        <span class="sr-only">Previous</span>
      </button>

      <button 
        type="button" 
        @click="nextImage"
        class="h-full cursor-pointer group focus:outline-none"
      >
        <svg class="w-5 h-5 text-gray-400 hover:text-gray-900" fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
        </svg>
        <span class="sr-only">Next</span>
      </button>
    </div>
  </div>

  <div v-else>
    <p class="text-center text-gray-500">No images available for this event.</p>
  </div>

      <!-- Product info -->
      <div class="mx-auto max-w-2xl px-4 pt-10 pb-16 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto_auto_1fr] lg:gap-x-8 lg:px-8 lg:pt-16 lg:pb-24">
        <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
          <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ event.title }}</h1>
        </div>

        <!-- Options -->
        <div class="mt-4 lg:row-span-3 lg:mt-0">
          <h2 class="sr-only">Restaurant information</h2>
          <p class="text-3xl tracking-tight text-gray-900">Event date: {{ event.event_date }}</p>

          <!-- Reviews -->
          <!-- <div class="mt-6">
            <h3 class="sr-only">Reviews</h3>
            <div class="flex items-center">
              <div class="flex items-center">
                <StarIcon v-for="rating in [0, 1, 2, 3, 4]" :key="rating" :class="[reviews.average > rating ? 'text-gray-900' : 'text-gray-200', 'size-5 shrink-0']" aria-hidden="true" />
              </div>
              <p class="sr-only">{{ reviews.average }} out of 5 stars</p>
              <a :href="reviews.href" class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ reviews.totalCount }} reviews</a>
            </div>
          </div> -->

          <form class="mt-10">
            <!-- Colors -->
            <!-- <div>
              <h3 class="text-sm font-medium text-gray-900">Color</h3>

              <fieldset aria-label="Choose a color" class="mt-4">
                <RadioGroup v-model="selectedColor" class="flex items-center gap-x-3">
                  <RadioGroupOption as="template" v-for="color in product.colors" :key="color.name" :value="color" :aria-label="color.name" v-slot="{ active, checked }">
                    <div :class="[color.selectedClass, active && checked ? 'ring-3 ring-offset-1' : '', !active && checked ? 'ring-2' : '', 'relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-hidden']">
                      <span aria-hidden="true" :class="[color.class, 'size-8 rounded-full border border-black/10']" />
                    </div>
                  </RadioGroupOption>
                </RadioGroup>
              </fieldset>
            </div> -->

            <!-- Sizes -->
            <!-- <div class="mt-10">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900">Size</h3>
                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Size guide</a>
              </div>

              <fieldset aria-label="Choose a size" class="mt-4">
                <RadioGroup v-model="selectedSize" class="grid grid-cols-4 gap-4 sm:grid-cols-8 lg:grid-cols-4">
                  <RadioGroupOption as="template" v-for="size in product.sizes" :key="size.name" :value="size" :disabled="!size.inStock" v-slot="{ active, checked }">
                    <div :class="[size.inStock ? 'cursor-pointer bg-white text-gray-900 shadow-xs' : 'cursor-not-allowed bg-gray-50 text-gray-200', active ? 'ring-2 ring-indigo-500' : '', 'group relative flex items-center justify-center rounded-md border px-4 py-3 text-sm font-medium uppercase hover:bg-gray-50 focus:outline-hidden sm:flex-1 sm:py-6']">
                      <span>{{ size.name }}</span>
                      <span v-if="size.inStock" :class="[active ? 'border' : 'border-2', checked ? 'border-indigo-500' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-md']" aria-hidden="true" />
                      <span v-else aria-hidden="true" class="pointer-events-none absolute -inset-px rounded-md border-2 border-gray-200">
                        <svg class="absolute inset-0 size-full stroke-2 text-gray-200" viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
                          <line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke" />
                        </svg>
                      </span>
                    </div>
                  </RadioGroupOption>
                </RadioGroup>
              </fieldset>
            </div> -->

            <!-- <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">Reserve</button> -->
          </form>
        </div>

        <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pt-6 lg:pr-8 lg:pb-16">
          <!-- Description and details -->
          <div>
            <h3 class="sr-only">Description</h3>

            <div class="space-y-6">
              <p class="text-base text-gray-900">{{ event.description }}</p>
            </div>
          </div>

          <!-- <div class="mt-10">
            <h3 class="text-sm font-medium text-gray-900">Highlights</h3>

            <div class="mt-4">
              <ul role="list" class="list-disc space-y-2 pl-4 text-sm">
                <li v-for="highlight in product.highlights" :key="highlight" class="text-gray-400">
                  <span class="text-gray-600">{{ highlight }}</span>
                </li>
              </ul>
            </div>
          </div> -->

          <!-- <div class="mt-10">
            <h2 class="text-sm font-medium text-gray-900">Details</h2>

            <div class="mt-4 space-y-6">
              <p class="text-sm text-gray-600">{{ product.details }}</p>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
  </NoHeroLayout>
</template>
