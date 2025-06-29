<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import { computed } from 'vue';

const currentUserId = usePage().props.auth.user.id;
const users = ref(usePage().props.users.filter(u => u.id !== currentUserId));
const selectedUser = ref(null);
const dialogVisible = ref(false);
const isAddUser = ref(false);
const editMode = ref(false);
const showPassword = ref(false);

// user data
const id = ref('');
const name = ref('');
const email = ref('');
const password = ref('');
const selectedRole = ref('');

const resetFormData = () => {
    id.value = '';
    name.value = '';
    email.value = '';
    password.value = '';
    selectedRole.value = '';
};

const openAddModal = () => {
    resetFormData();
    isAddUser.value = true;
    editMode.value = false;
    dialogVisible.value = true;
};

const AddUser = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('email', email.value);
    formData.append('password', password.value);
    formData.append('isAdmin', selectedRole.value === 'admin' ? 1 : 0);
    formData.append('isRestaurant', selectedRole.value === 'restaurant' ? 1 : 0);

    try {
        const response = await axios.post('/admin/users/store', formData);
        users.value.push(response.data.user);
        dialogVisible.value = false;
        resetFormData();
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            title: response.data.message,
        });
    } catch (err) {
        console.error(err);
    }
};

const openEditModal = (user) => {
    id.value = user.id;
    name.value = user.name;
    email.value = user.email;
    password.value = '********';
    selectedRole.value = user.isAdmin ? 'admin' : (user.isRestaurant ? 'restaurant' : '');
    editMode.value = true;
    isAddUser.value = false;
    dialogVisible.value = true;
};

const updateUser = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('email', email.value);
    if (password.value) formData.append('password', password.value);
    formData.append('isAdmin', selectedRole.value === 'admin' ? 1 : 0);
    formData.append('isRestaurant', selectedRole.value === 'restaurant' ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        const response = await axios.post('/admin/users/' + id.value, formData);
        const updatedUser = response.data.user;
        const index = users.value.findIndex(u => u.id === updatedUser.id);
        if (index !== -1) users.value[index] = updatedUser;

        dialogVisible.value = false;
        resetFormData();
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            title: response.data.message,
        });
    } catch (err) {
        console.error(err);
    }
};

const deleteUser = async (user) => {
    const confirmed = await Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the user.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
    });

    if (!confirmed.isConfirmed) return;

    try {
        await axios.delete('/admin/users/destroy/' + user.id);
        users.value = users.value.filter(u => u.id !== user.id);
        Swal.fire({
            toast: true,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            title: 'User deleted',
        });
    } catch (err) {
        console.error('Delete failed:', err);
    }
};

const searchQuery = ref('');

const filteredUsers = computed(() => {
  return users.value.filter(user =>
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <!-- dialog for adding or editing event -->
            <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit user' : 'Add user'"
            width="500"
            :before-close="handleClose"
            class="dark:bg-gray-900 dark:text-white"
            >
            <div class="bg-white dark:bg-gray-800 dark:text-white p-4 rounded-lg">
            <!-- form start -->
            <form @submit.prevent="editMode ? updateUser():AddUser()" class="max-w-md mx-auto">
            <!-- Name -->
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="name" type="text" name="floating_name" id="floating_name"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " required />
                <label for="floating_name"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
            </div>

            <!-- Email -->
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="email" type="email" name="floating_email" id="floating_email"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " required />
                <label for="floating_email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
            </div>

            <!-- Password -->
            <div class="relative z-0 w-full mb-5 group">
            <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                name="floating_password"
                id="floating_password"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                    dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer pr-10"
                placeholder=" "
                :required="!editMode"
            />
            <label for="floating_password"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0]
                    peer-focus:start-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0
                    peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>

            <!-- Toggle Button -->
            <button type="button"
                class="absolute right-0 top-2 text-gray-500 dark:text-gray-300 px-2 focus:outline-none"
                @click="showPassword = !showPassword"
            >
                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.157-3.362M6.117 6.117A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.957 9.957 0 01-1.308 2.093M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>
            </button>
            </div>


            <!-- Role -->
            <div class="relative z-0 w-full mb-5 group">
            <label for="role_select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select role</label>
            <select
                id="role_select"
                v-model="selectedRole"
                class="block w-full text-sm rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-blue-500 focus:border-blue-500
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                    py-2.5 px-3"
            >
                <option value="" disabled>Select role</option>
                <option value="admin">Admin</option>
                <option value="restaurant">Restaurant</option>
            </select>
            </div>

            <button type="submit"
                class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Submit
            </button>
            </form>
            <!-- form end -->
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
                            <input v-model="searchQuery" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search by name"/>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button type="button" @click="openAddModal" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add user
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">Name</th>
            <th scope="col" class="px-4 py-3">Email</th>
            <th scope="col" class="px-4 py-3">Role</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="user in filteredUsers" :key="user.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ user.name }}</th>
            <td class="px-4 py-3">{{user.email}}</td>
            <td class="px-4 py-3">
                <span v-if="user.isAdmin">Admin</span>
                <span v-else-if="user.isRestaurant">Restaurant</span>
                <span v-else>—</span>
            </td>

            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + user.id" :data-dropdown-toggle="'dropdown-' + user.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + user.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + user.id">
                        <li>
                            <a href="#" @click="openEditModal(user)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteUser(user, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
            </div>
        </div>
    </div>
    </section>
</template>