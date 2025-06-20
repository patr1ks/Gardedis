<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({ reservations: Array });
const reservations = ref(props.reservations);

const cancelReservation = async (reservation) => {
  try {
    await axios.post(`/reservations/${reservation.id}/cancel`);
    reservation.status = 'cancelled';
  } catch (error) {
    console.error('Failed to cancel reservation:', error);
  }
};

const payForReservation = (reservationId) => {
  window.location.href = `/reservations/pay/${reservationId}`;
};
</script>

<template>
  <AuthenticatedLayout>
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
              <strong>Restaurant:</strong> {{ reservation.restaurant?.title }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
              <strong>Table:</strong> {{ reservation.table_number }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
              <strong>Date:</strong>
              {{
                new Date(reservation.reservation_date).toLocaleDateString('lv-LV', {
                  day: '2-digit',
                  month: '2-digit',
                  year: 'numeric'
                })
              }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300">
              <strong>Time:</strong> {{ reservation.time }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
              <strong>Status:</strong> {{ reservation.status }}
            </p>

            <div class="flex gap-2">
              <button
                v-if="reservation.status !== 'cancelled'"
                @click="cancelReservation(reservation)"
                class="text-sm px-4 py-1 rounded bg-red-600 text-white hover:bg-red-700"
              >
                Cancel
              </button>

              <button
                v-if="reservation.status === 'payment pending'"
                @click="payForReservation(reservation.id)"
                class="text-sm px-4 py-1 rounded bg-green-600 text-white hover:bg-green-700"
              >
                Pay
              </button>
            </div>
          </div>
        </div>
        <div v-else class="text-gray-500 dark:text-gray-400">
          You have no reservations yet.
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
