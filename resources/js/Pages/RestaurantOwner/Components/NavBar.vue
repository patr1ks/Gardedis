<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const authUser = usePage().props.auth.user;
</script>

<template>
    <nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-800 dark:border-gray-700 fixed left-0 right-0 top-0 z-50">
      <div class="flex flex-wrap justify-between items-center">
        <div class="flex justify-start items-center">
          <button
            data-drawer-target="drawer-navigation"
            data-drawer-toggle="drawer-navigation"
            aria-controls="drawer-navigation"
            class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          >
            <svg
              aria-hidden="true"
              class="w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <svg
              aria-hidden="true"
              class="hidden w-6 h-6"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <span class="sr-only">Toggle sidebar</span>
          </button>
          <Link :href="route('restaurantOwner.dashboard')" class="flex items-center justify-between mr-4">
            <svg height="30px" viewBox="0 0 512 512" width="30px" xmlns="http://www.w3.org/2000/svg"><title/><path d="M357.57,223.94a79.48,79.48,0,0,0,56.58-23.44l77-76.95c6.09-6.09,6.65-16,.85-22.39a16,16,0,0,0-23.17-.56L400.2,169.18a12.29,12.29,0,0,1-17.37,0c-4.79-4.78-4.53-12.86.25-17.64l68.33-68.33a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,440.27,56a16.71,16.71,0,0,0-11.81,4.9l-68.27,68.26a12.29,12.29,0,0,1-17.37,0c-4.78-4.78-4.53-12.86.25-17.64L411.4,43.21a16,16,0,0,0-.56-23.16A15.62,15.62,0,0,0,400.26,16a16.73,16.73,0,0,0-11.81,4.9L311.5,97.85a79.49,79.49,0,0,0-23.44,56.59v8.23A16,16,0,0,1,283.37,174l-35.61,35.62a4,4,0,0,1-5.66,0L68.82,36.33a16,16,0,0,0-22.58-.06C31.09,51.28,23,72.47,23,97.54c-.1,41.4,21.66,89,56.79,124.08l85.45,85.45A64.79,64.79,0,0,0,211,326a64,64,0,0,0,16.21-2.08,16.24,16.24,0,0,1,4.07-.53,15.93,15.93,0,0,1,10.83,4.25l11.39,10.52a16.12,16.12,0,0,1,4.6,11.23v5.54a47.73,47.73,0,0,0,13.77,33.65l90.05,91.57.09.1a53.29,53.29,0,0,0,75.36-75.37L302.39,269.9a4,4,0,0,1,0-5.66L338,228.63a16,16,0,0,1,11.32-4.69Z"/><path d="M211,358a97.32,97.32,0,0,1-68.36-28.25l-13.86-13.86a8,8,0,0,0-11.3,0l-85,84.56c-15.15,15.15-20.56,37.45-13.06,59.29a30.63,30.63,0,0,0,1.49,3.6C31,484,50.58,496,72,496a55.68,55.68,0,0,0,39.64-16.44L225,365.66a4.69,4.69,0,0,0,1.32-3.72l0-.26a4.63,4.63,0,0,0-5.15-4.27A97.09,97.09,0,0,1,211,358Z"/></svg>
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white pl-3">Gardēdis</span>
          </Link>
        </div>
        <div class="flex items-center lg:order-2">
          <!-- Dropdown menu -->
          <button
            type="button"
            class="flex mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button"
            aria-expanded="false"
            data-dropdown-toggle="dropdown"
          >
            <span class="sr-only">Open user menu</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 rounded-full bg-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
          </button>
          <!-- Dropdown menu -->
          <div
            class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
            id="dropdown"
          >
          <div class="px-4 py-3">
                <span class="block text-sm text-gray-900 dark:text-white">
                    {{ authUser?.name }}
                </span>
                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">
                    {{ authUser?.email }}
                </span>
            </div>
            <ul
              class="py-1 text-gray-700 dark:text-gray-300"
              aria-labelledby="dropdown"
            >
              <li>
                <Link :href="route('profile.edit')" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
                  Profile
                </Link>
              </li>
            </ul>
            <ul
              class="py-1 text-gray-700 dark:text-gray-300"
              aria-labelledby="dropdown"
            >
              <li>
                <Link :href="route('logout')" method="post" as="button"
                  class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                  >Sign out</Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
</template>