<script setup>
import NoHeroLayout from './Layouts/NoHeroLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const name = ref('');
const address = ref('');
const contacts = ref('');

const submitForm = () => {
  router.post('/application/store', {
    name: name.value,
    address: address.value,
    contacts: contacts.value,
  }, {
    onSuccess: (page) => {
      Swal.fire({
        toast: true,
        icon: 'success',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        title: page.props.flash?.success || 'Application submitted!',
      });

      name.value = '';
      address.value = '';
      contacts.value = '';
    }
  });
};
</script>

<template>
  <NoHeroLayout>
    <div class="flex flex-col bg-white dark:bg-gray-900 transition-colors duration-300 pb-11">
      
      <!-- Form Section -->
      <div class="flex-grow py-24">
        <form @submit.prevent="submitForm" class="max-w-md mx-auto px-4">
          
          <!-- Name -->
          <div class="relative z-0 w-full mb-5 group">
            <input
              v-model="name"
              type="text"
              id="floating_name"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer transition-colors"
              placeholder=" "
              required
            />
            <label
              for="floating_name"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Restaurant Name
            </label>
          </div>

          <!-- Address -->
          <div class="relative z-0 w-full mb-5 group">
            <input
              v-model="address"
              type="text"
              id="floating_address"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer transition-colors"
              placeholder=" "
              required
            />
            <label
              for="floating_address"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Address
            </label>
          </div>

          <!-- Contacts -->
          <div class="relative z-0 w-full mb-5 group">
            <input
              v-model="contacts"
              type="text"
              id="floating_contacts"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer transition-colors"
              placeholder=" "
              required
            />
            <label
              for="floating_contacts"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Contacts (Email or Phone)
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                   font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center
                   dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
            Submit
          </button>
        </form>
      </div>

    </div>
  </NoHeroLayout>
</template>