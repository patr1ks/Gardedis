<script setup>
defineProps({ reservations: Array });
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-4">
      <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Your Reservations</h2>
        <div v-if="reservations.length > 0" class="space-y-4">
          <div
            v-for="reservation in reservations"
            :key="reservation.id"
            class="border border-gray-200 dark:border-gray-700 rounded-md p-4"
          >
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Restaurant:</strong> {{ reservation.restaurant?.title}}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Table:</strong> {{ reservation.table_number }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Date:</strong> {{ reservation.reservation_date }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Time:</strong> {{ reservation.time }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                <strong>Status:</strong> {{ reservation.status }}
            </p>
          </div>
        </div>
        <div v-else class="text-gray-500 dark:text-gray-400">
          You have no reservations yet.
        </div>
      </div>
    </section>
  </template>  
