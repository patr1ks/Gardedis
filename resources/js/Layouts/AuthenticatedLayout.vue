<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { MoonIcon, SunIcon } from '@heroicons/vue/24/solid';
import Footer from '../Pages/User/Layouts/Footer.vue';

const showingNavigationDropdown = ref(false);

const isDark = ref(false)

const toggleDarkMode = () => {
  document.documentElement.classList.toggle('dark')
  isDark.value = document.documentElement.classList.contains('dark')
  localStorage.setItem('darkMode', isDark.value)
}

onMounted(() => {
  isDark.value = localStorage.getItem('darkMode') === 'true'
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  }
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <nav class="bg-white border-b border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

          <!-- Left: Logo + Nav -->
          <div class="flex items-center space-x-6">
            <Link :href="route('user.home')" class="flex items-center">
              <ApplicationLogo class="h-8 w-8 text-blue-700" />
              <span class="ml-2 text-lg font-semibold tracking-wide text-gray-900 dark:text-white">Gardēdis</span>
            </Link>

            <div class="hidden sm:flex space-x-4">
              <Link :href="route('profile.edit')"
                :class="['text-sm font-medium block py-2 px-3 rounded-sm md:p-0',
                  route().current('dashboard') 
                    ? 'text-blue-700 dark:text-blue-500' 
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700']">
                Profile
              </Link>
              <Link :href="route('user.reservations')"
                :class="['text-sm font-medium block py-2 px-3 rounded-sm md:p-0',
                  route().current('dashboard') 
                    ? 'text-blue-700 dark:text-blue-500' 
                    : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700']">
                Reservations
              </Link>
            </div>
          </div>

          <!-- Right: Dark Mode Button + Dropdown -->
          <div class="hidden sm:flex sm:items-center gap-4">

            <!-- Dark Mode Button -->
            <div class="px-2">
              <button @click="toggleDarkMode" class="relative w-12 h-12 rounded-full flex items-center justify-center transition bg-white dark:bg-gray-800 shadow-md">
                  <transition name="fade" mode="out-in">
                      <component :is="isDark ? MoonIcon : SunIcon" :key="isDark" class="w-6 h-6 text-yellow-400 dark:text-gray-200"/>
                  </transition>
              </button>
            </div>

            <!-- User Dropdown -->
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white focus:outline-none transition"
                >
                  {{ $page.props.auth.user.name }}
                  <svg
                    class="ms-2 -me-0.5 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </template>

              <template #content>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>

          <!-- Mobile menu button -->
          <div class="sm:hidden flex items-center">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white focus:outline-none"
            >
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  v-if="!showingNavigationDropdown"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Nav -->
      <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden px-4 pb-4">
        <div class="pt-4 space-y-1">
          <Link
            :href="route('dashboard')"
            class="block py-2 px-3 text-gray-900 dark:text-white rounded-sm hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            Dashboard
          </Link>
        </div>

        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
          <div class="text-gray-700 dark:text-gray-300 font-medium text-base">
            {{ $page.props.auth.user.name }}
          </div>
          <div class="text-sm text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    <header class="bg-white dark:bg-gray-800 shadow transition-colors" v-if="$slots.header">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <!-- Page Content -->
    <main class="py-6">
      <slot />
    </main>
    <Footer></Footer>
  </div>
</template>