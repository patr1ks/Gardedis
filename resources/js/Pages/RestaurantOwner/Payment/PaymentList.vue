<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const reservations = usePage().props.reservations;

const dropdownOpen = ref(null);
const statusOptions = ['pending', 'confirmed', 'cancelled'];

const toggleDropdown = (id) => {
  dropdownOpen.value = dropdownOpen.value === id ? null : id;
};

const updateStatus = async (id, newStatus) => {
  try {
    await axios.put(`/restaurant-owner/payments/update-status/${id}`, { status: newStatus });
    const res = reservations.find(r => r.id === id);
    if (res) res.status = newStatus;
    dropdownOpen.value = null;
  } catch (error) {
    console.error('Failed to update status:', error);
  }
};
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                  <tr>
                    <th class="px-4 py-3">User Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Table Number</th>
                    <th class="px-4 py-3">Time</th>
                    <th class="px-4 py-3">Reservation Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="reservation in reservations"
                    :key="reservation.id"
                    class="border-b dark:border-gray-700"
                  >
                    <td class="px-4 py-3">
                      {{ reservation.user?.email || '—' }}
                    </td>

                    <td class="px-4 py-3 capitalize">
                      <div class="relative inline-block">
                        <button
                          @click="toggleDropdown(reservation.id)"
                          class="text-sm capitalize px-2 py-1 rounded border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        >
                          {{ reservation.status }}
                        </button>
                        <div
                          v-if="dropdownOpen === reservation.id"
                          class="absolute z-10 mt-1 w-32 bg-white border border-gray-200 rounded shadow dark:bg-gray-800 dark:border-gray-600"
                        >
                        <ul class="text-sm text-gray-700 dark:text-gray-200">
                          <li
                            v-for="status in statusOptions"
                            :key="status"
                            @click="updateStatus(reservation.id, status)"
                            class="block w-full cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600"
                          >
                            {{ status }}
                          </li>
                        </ul>
                        </div>
                      </div>
                    </td>

                    <td class="px-4 py-3">
                      {{ reservation.table_number || '—' }}
                    </td>

                    <td class="px-4 py-3">
                      {{ reservation.time || '—' }}
                    </td>

                    <td class="px-4 py-3">
                      {{
                        new Date(reservation.reservation_date).toLocaleDateString('lv-LV', {
                          day: '2-digit',
                          month: '2-digit',
                          year: 'numeric'
                        })
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
    </div>
    </section>
</template>