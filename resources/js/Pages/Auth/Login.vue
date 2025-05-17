<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const canRegister = usePage().props.canRegister;

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                title: page.props.flash?.success || 'Logged in successfully!',
            });
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-semibold text-green-500">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="bg-white p-8 w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Log in!</h2>

            <div class="mb-4">
                <InputLabel for="email" value="Email" class="text-gray-700" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-blue-400 focus:ring-blue-400"
                    v-model="form.email"
                    required
                    autofocus
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
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-gray-600">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-gray-300 text-blue-500 focus:ring-blue-400" />
                    <span class="ms-2">Remember me</span>
                </label>
            </div> -->
            <div class="flex items-center justify-between mb-6">
                <Link
                    :href="route('register')" v-if="canRegister"
                    class="text-sm text-blue-500 hover:underline"
                >
                    Don't have an account? Register
                </Link>
            </div>

            <button
                type="submit"
                class="w-full py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-semibold text-lg transition"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Log in
            </button>

        </form>
    </GuestLayout>
</template>
