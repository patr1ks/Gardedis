<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <footer class="bg-white dark:bg-gray-900 shadow-sm transition-colors">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <a
          href="https://flowbite.com/"
          class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse"
        >
          <svg height="30px" viewBox="0 0 512 512" width="30px" xmlns="http://www.w3.org/2000/svg">
            <title />
            <path
              d="M357.57,223.94a79.48,79.48,0,0,0,56.58-23.44l77-76.95c6.09-6.09,6.65-16,.85-22.39a16,16,0,0,0-23.17-.56L400.2,169.18a12.29,12.29,0,0,1-17.37,0c-4.79-4.78-4.53-12.86.25-17.64l68.33-68.33a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,440.27,56a16.71,16.71,0,0,0-11.81,4.9l-68.27,68.26a12.29,12.29,0,0,1-17.37,0c-4.78-4.78-4.53-12.86.25-17.64L411.4,43.21a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,400.26,16a16.73,16.73,0,0,0-11.81,4.9L311.5,97.85a79.49,79.49,0,0,0-23.44,56.59v8.23A16,16,0,0,1,283.37,174l-35.61,35.62a4,4,0,0,1-5.66,0L68.82,36.33a16,16,0,0,0-22.58-.06C31.09,51.28,23,72.47,23,97.54c-.1,41.4,21.66,89,56.79,124.08l85.45,85.45A64.79,64.79,0,0,0,211,326a64,64,0,0,0,16.21-2.08,16.24,16.24,0,0,1,4.07-.53,15.93,15.93,0,0,1,10.83,4.25l11.39,10.52a16.12,16.12,0,0,1,4.6,11.23v5.54a47.73,47.73,0,0,0,13.77,33.65l90.05,91.57.09.1a53.29,53.29,0,0,0,75.36-75.37L302.39,269.9a4,4,0,0,1,0-5.66L338,228.63a16,16,0,0,1,11.32-4.69Z"
            />
            <path
              d="M211,358a97.32,97.32,0,0,1-68.36-28.25l-13.86-13.86a8,8,0,0,0-11.3,0l-85,84.56c-15.15,15.15-20.56,37.45-13.06,59.29a30.63,30.63,0,0,0,1.49,3.6C31,484,50.58,496,72,496a55.68,55.68,0,0,0,39.64-16.44L225,365.66a4.69,4.69,0,0,0,1.32-3.72l0-.26a4.63,4.63,0,0,0-5.15-4.27A97.09,97.09,0,0,1,211,358Z"
            />
          </svg>
          <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">
            Gardēdis
          </span>
        </a>

        <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
          <li>
            <Link :href="route('user.about')" class="hover:underline me-4 md:me-6">About us</Link>
          </li>
          <li>
            <Link :href="route('user.contacts')" class="hover:underline">Contacts</Link>
          </li>
        </ul>
      </div>

      <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />

      <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">
        © 2025 Gardēdis™. All Rights Reserved.
      </span>
    </div>
  </footer>
</template>
