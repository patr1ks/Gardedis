<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <div class="py-12 bg-white dark:bg-gray-900 transition-colors duration-300">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Profile Information -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-300">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </div>

        <!-- Update Password -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-300">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <!-- Delete Account -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg transition-colors duration-300">
          <DeleteUserForm class="max-w-xl" />
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>