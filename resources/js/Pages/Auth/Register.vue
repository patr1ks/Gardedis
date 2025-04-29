<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="bg-white p-8 w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>

            <div class="mb-4">
                <InputLabel for="name" value="Name" class="text-gray-700" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring-blue-400"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mb-4">
                <InputLabel for="email" value="Email" class="text-gray-700" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring-blue-400"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-4">
                <InputLabel for="password" value="Password" class="text-gray-700" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring-blue-400"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mb-6">
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring-blue-400"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-between mb-4">
                <Link
                    :href="route('login')"
                    class="text-sm text-blue-500 hover:underline"
                >
                    Already registered?
                </Link>
            </div>

            <PrimaryButton
                class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg transition"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Register
            </PrimaryButton>
        </form>
    </GuestLayout>
</template>
