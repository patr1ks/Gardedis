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
    const response = await axios.get(`/admin/payments/show-data/${id}`)
    selectedPayment.value = response.data.payment
    showPaymentDialog.value = true
  } catch (error) {
    console.error('Failed to load payment data:', error)
  }
}
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <!-- dialog for adding or editing restaurant -->
        <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit restaurant' : 'Add restaurant'"
            width="500"
            :before-close="handleClose"
        >
            <!-- form start -->
            <form @submit.prevent="editMode ? updateRestaurant():AddRestaurant()" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="title" type="text" name="floating_title" id="floating_title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="price" type="text" name="floating_price" id="floating_price" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_price" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
            </div>

            <!-- category select -->
            <div class="relative z-0 w-full mb-5 group">
                <label for="underline_select">Select category</label>
                <select id="underline_select" v-model="category_id" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option v-for="category in categories" :key="category.id" :value="category.id" selected>{{category.name}}</option>
                </select>
            </div>

            <div>
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                <textarea id="message" v-model="description" rows="4" class=" block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write description"></textarea>
            </div>

            <!-- image upload -->
            <div class="grid md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Images</label>
                    <el-upload
                        v-model:file-list="restaurantImages"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :on-change="handleFileChange"
                        accept="image/*"
                        :auto-upload="false"
                    >
                        <el-icon><Plus /></el-icon>
                    </el-upload>

                </div>
            </div>

            <!-- list of images for selected product -->
             <div class="flex flex-nowrap mb-8">
                <div v-for="(rimage, index) in restaurant_images" :key="rimage.id" class="relative w-32 h-32">
                    <img class="w-24 h-24 rounded-sm" :src="`/${rimage.image}`" alt="">
                    <span class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                        <span @click="deleteImage(rimage, index)" class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer">x

                        </span>
                    </span>
                </div>
             </div>

            <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
            <!-- form end -->
        
        </el-dialog>

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
                        <th class="px-4 py-3 text-right">Actions</th>
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
                            €{{ payment.price }}
                        </td>
                        <td class="px-4 py-3 capitalize">
                            {{ payment.status }}
                        </td>
                        <td class="px-4 py-3">
                            {{ new Date(payment.created_at).toLocaleDateString() }}
                        </td>

                        <td class="px-4 py-3 text-right">
                            <button
                            class="text-blue-600 hover:underline"
                            @click.prevent="openPaymentDetails(payment.id)"
                            >
                            Details
                            </button>
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