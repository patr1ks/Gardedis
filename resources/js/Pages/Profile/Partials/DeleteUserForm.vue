<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-6 space-y-6">
        <header>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Delete Account</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Once your account is deleted, all of its resources and data will be permanently removed. Please back up any information you want to keep.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-md">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    This action is permanent. Please enter your password to confirm.
                </p>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-red-500 focus:border-red-500"
                        placeholder="Enter your password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                    <DangerButton
                        :disabled="form.processing"
                        :class="{ 'opacity-25': form.processing }"
                        @click="deleteUser"
                    >
                        Confirm Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
