<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
import axios from 'axios'

const payments = usePage().props.payments

const selectedPayment = ref(null)
const showPaymentDialog = ref(false)

// Open payment details modal
const openPaymentDetails = async (id) => {
  try {
    const response = await axios.get(`/restaurant-owner/payments/show-data/${id}`)
    selectedPayment.value = response.data.payment
    showPaymentDialog.value = true
  } catch (error) {
    console.error('Failed to load payment data:', error)
  }
}

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
        <el-dialog
            v-model="showPaymentDialog"
            title="Payment Details"
            width="500"
            :before-close="() => showPaymentDialog.value = false"
            >
            <div v-if="selectedPayment">
                <p><strong>Amount:</strong> €{{ selectedPayment.amount }}</p>
                <p><strong>Status:</strong> {{ selectedPayment.status }}</p>
                <p><strong>Method:</strong> {{ selectedPayment.method }}</p>
                <p><strong>User:</strong> {{ selectedPayment.user?.name }}</p>
                <p><strong>Restaurant:</strong> {{ selectedPayment.restaurant?.title }}</p>
                <p><strong>Created At:</strong> {{ new Date(selectedPayment.created_at).toLocaleString() }}</p>
            </div>
        </el-dialog>


    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" required>
                        </div>
                    </form>
                </div>
            </div>
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
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
            </nav>
        </div>
    </div>
    </section>
</template>