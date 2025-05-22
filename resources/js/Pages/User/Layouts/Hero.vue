<template>
    <section class="relative bg-white dark:bg-gray-900 transition-colors duration-300">
      <!-- Background Image -->
      <div class="absolute inset-0">
        <img
          src="https://images.pexels.com/photos/260922/pexels-photo-260922.jpeg"
          alt="Restaurant"
          class="w-full h-full object-cover"
        />
        <!-- White or dark overlay gradient -->
        <div
          class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-transparent dark:from-gray-900 dark:via-gray-900/70 dark:to-transparent transition-colors"
        ></div>
      </div>
  
      <!-- Content -->
      <div
        class="relative grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-32 lg:grid-cols-12"
      >
        <div class="mr-auto place-self-center lg:col-span-7">
          <h1
            class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl text-gray-900 dark:text-white transition-colors"
          >
            Discover and Book the Perfect Restaurant
          </h1>
          <p
            class="max-w-2xl mb-6 font-light text-gray-700 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-300 transition-colors"
          >
            Explore top dining spots, filter by your preferences, and reserve your table effortlessly with Gardēdis.
          </p>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
          <!-- Reserved for future content or spacing -->
        </div>
      </div>
    </section>
  </template>  