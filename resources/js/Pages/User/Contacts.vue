<script setup>
import NoHeroLayout from './Layouts/NoHeroLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const first_name = ref('');
const last_name = ref('');
const email = ref('');
const telephone = ref('');
const message = ref('');

const submitForm = () => {
  router.post('/contacts/store', {
    first_name: first_name.value,
    last_name: last_name.value,
    email: email.value,
    telephone: telephone.value,
    message: message.value,
  }, {
    onSuccess: (page) => {
      Swal.fire({
        toast: true,
        icon: 'success',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        title: page.props.flash?.success || 'Message sent!',
      });

      first_name.value = '';
      last_name.value = '';
      email.value = '';
      telephone.value = '';
      message.value = '';
    }
  });
};
</script>

<template>
  <NoHeroLayout>
    <div class="flex flex-col min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="flex-grow py-24 px-4">
        <form @submit.prevent="submitForm" class="max-w-md mx-auto">

          <!-- First Name -->
          <div class="relative z-0 w-full mb-5 group">
            <input v-model="first_name" type="text" id="floating_first_name"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer"
              placeholder=" " required />
            <label for="floating_first_name"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              First Name
            </label>
          </div>

          <!-- Last Name -->
          <div class="relative z-0 w-full mb-5 group">
            <input v-model="last_name" type="text" id="floating_last_name"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer"
              placeholder=" " required />
            <label for="floating_last_name"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Last Name
            </label>
          </div>

          <!-- Email -->
          <div class="relative z-0 w-full mb-5 group">
            <input v-model="email" type="email" id="floating_email"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer"
              placeholder=" " required />
            <label for="floating_email"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Email
            </label>
          </div>

          <!-- Telephone -->
          <div class="relative z-0 w-full mb-5 group">
            <input v-model="telephone" type="text" id="floating_telephone" maxlength="8"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer"
              placeholder=" " required />
            <label for="floating_telephone"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Telephone
            </label>
          </div>

          <!-- Message -->
          <div class="relative z-0 w-full mb-5 group">
            <textarea v-model="message" id="floating_message" rows="4"
              class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                     dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0
                     focus:border-blue-600 peer"
              placeholder=" " required></textarea>
            <label for="floating_message"
              class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                     -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-placeholder-shown:scale-100
                     peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6
                     peer-focus:text-blue-600 peer-focus:dark:text-blue-500">
              Message
            </label>
          </div>

          <!-- Submit Button -->
          <button type="submit"
            class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                   font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center
                   dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
          </button>
        </form>
      </div>
    </div>
  </NoHeroLayout>
</template>