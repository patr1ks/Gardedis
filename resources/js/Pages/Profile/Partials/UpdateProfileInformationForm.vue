<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-6">
        <header class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                Update your account's username and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
            <div>
                <InputLabel for="name" value="Username" class="text-sm text-gray-700 dark:text-gray-300" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-sm text-gray-700 dark:text-gray-300" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="text-sm text-yellow-600 dark:text-yellow-400">
                Your email address is unverified.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="underline text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 ml-1"
                >
                    Click here to re-send the verification email.
                </Link>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" :disabled="form.processing" :class="['px-5 py-2.5 rounded-lg text-sm font-medium text-white','bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300','dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',form.processing && 'opacity-50 cursor-not-allowed']">
                    Save
                </button>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
