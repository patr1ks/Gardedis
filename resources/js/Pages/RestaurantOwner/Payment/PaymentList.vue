<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
import axios from 'axios'

const payments = usePage().props.payments

const selectedPayment = ref(null)

const dropdownOpen = ref(null)
const statusOptions = ['pending', 'paid', 'cancelled']

const toggleDropdown = (id) => {
  dropdownOpen.value = dropdownOpen.value === id ? null : id
}

const updateStatus = async (id, newStatus) => {
  try {
    await axios.put(`/restaurant-owner/payments/update-status/${id}`, { status: newStatus })
    const payment = payments.find(p => p.id === id)
    if (payment) payment.status = newStatus
    dropdownOpen.value = null
  } catch (error) {
    console.error('Failed to update status:', error)
  }
}
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
                        <th class="px-4 py-3">Restaurant</th>
                        <th class="px-4 py-3">User Email</th>
                        <th class="px-4 py-3">Price (€)</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <!-- <th class="px-4 py-3 text-right">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                        v-for="(payment, index) in payments"
                        :key="payment.id"
                        class="border-b dark:border-gray-700"
                        >
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                            {{ payment.reservation?.restaurant?.title || '—' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ payment.user?.email || '—' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ payment.price }}€
                        </td>
                        <td class="px-4 py-3 capitalize">
  <div class="relative inline-block">
    <button
      @click="toggleDropdown(payment.id)"
      class="text-sm capitalize px-2 py-1 rounded border border-gray-300 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
    >
      {{ payment.status }}
    </button>
    <div
      v-if="dropdownOpen === payment.id"
      class="absolute z-10 mt-1 w-32 bg-white border border-gray-200 rounded shadow dark:bg-gray-800 dark:border-gray-600"
    >
      <ul class="text-sm text-gray-700 dark:text-gray-200">
        <li v-for="status in statusOptions" :key="status">
          <button
            @click="updateStatus(payment.id, status)"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600"
          >
            {{ status }}
          </button>
        </li>
      </ul>
    </div>
  </div>
</td>

                        <td class="px-4 py-3">
                            {{ new Date(payment.created_at).toLocaleDateString() }}
                        </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
    </section>
</template>