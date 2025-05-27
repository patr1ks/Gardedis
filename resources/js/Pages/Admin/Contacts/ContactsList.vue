<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
import axios from 'axios';
import { ElImage } from 'element-plus'
// import VueSweetalert2 from 'vue-sweetalert2';


defineProps({
    contacts: Array
})

const contacts = usePage().props.contacts;

const selectedContact = ref(null)


// contact data
const id = ref('');
const first_name = ref('');
const last_name = ref('');
const email = ref('');
const telephone = ref('');
const message = ref([]);


//delete contact
const deleteContact = async (contact, index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete('contacts/destroy/'+contact.id, {
                    onSuccess: (page) => {
                        this.delete(contact, index);
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success
                        });
                    }
                })
            } catch (error) {
                console.log(error);
            }
        }
    })
}
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">

    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">First name</th>
            <th scope="col" class="px-4 py-3">Last name</th>
            <th scope="col" class="px-4 py-3">Email</th>
            <th scope="col" class="px-4 py-3">Phone</th>
            <th scope="col" class="px-4 py-3">Message</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="contact in contacts" :key="contact.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ contact.first_name }}</th>
            <td class="px-4 py-3">{{contact.last_name}}</td>
            <td class="px-4 py-3">{{contact.email}}</td>
            <td class="px-4 py-3">{{contact.telephone}}</td>
            <td class="px-4 py-3">{{contact.message}}</td> 
            <td class="px-4 py-3">
                </td>
            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + contact.id" :data-dropdown-toggle="'dropdown-' + contact.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + contact.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + contact.id">
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteContact(contact, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
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

<style scoped>
.el-image-viewer__btn--rotate-left,
.el-image-viewer__btn--rotate-right {
  display: none !important;
}
</style>