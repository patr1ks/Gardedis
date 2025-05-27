<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
// import VueSweetalert2 from 'vue-sweetalert2';


defineProps({
    categories: Array
})


const categories = usePage().props.categories;

const selectedCategory = ref(null)

const isAddCategory = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);



// category data
const id = ref('');
const name = ref('');

// Open the add modal
const openAddModal = () => {
    resetFormData();
    isAddCategory.value = true;
    dialogVisible.value = true;
    editMode.value = false;
    
}  
const AddCategory = async () => {
    const formData = new FormData();
    formData.append('name', name.value);

    try {
        await router.post('categories/store', formData, {
            // headers: {
            //     'Content-Type': 'multipart/form-data'
            // },
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash?.success || 'Category added successfully!',
                });
                dialogVisible.value = false;
                resetFormData();
                router.visit('/admin/categories');
            },
        });
    } catch (err) {
        console.error(err);
    }
};


// reset data after adding category
const resetFormData = () => {
    id.value = '';
    name.value = '';
}

const openEditModal = (category) => {
    // console.log(category);
    editMode.value = true;
    dialogVisible.value = true;
    isAddCategory.value = false;

    //update data
    id.value = category.id;
    name.value = category.name;
}


// update category
const updateCategory = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('_method', 'PUT');

    try {
        await router.post('categories/update/'+id.value, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                resetFormData();
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
        
    }
};

//delete category
const deleteCategory = async (category, index) => {
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
                router.delete('categories/destroy/'+category.id, {
                    onSuccess: (page) => {
                        categories.splice(index, 1);
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
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 pb">
            <!-- dialog for adding or editing category -->
            <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit category' : 'Add category'"
            width="500"
            :before-close="handleClose"
            class="dark:bg-gray-900 dark:text-white"
            >
            <div class="bg-white dark:bg-gray-800 dark:text-white p-4 rounded-lg">
                <form @submit.prevent="editMode ? updateCategory() : AddCategory()" class="max-w-md mx-auto">
                <div class="relative z-0 w-full mb-5 group">
                    <input
                    v-model="name"
                    type="text"
                    name="floating_name"
                    id="floating_name"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                            dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                    required
                    />
                    <label
                    for="floating_name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                            -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-blue-600 peer-focus:dark:text-blue-500
                            peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >
                    Name
                    </label>
                </div>
                <button
                    type="submit"
                    class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                        font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600
                        dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
                </form>
            </div>
            </el-dialog>

    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex justify-end p-4">
                    <button
                    type="button"
                    @click="openAddModal"
                    class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                    <svg
                        class="h-3.5 w-3.5 mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                    >
                        <path
                        clip-rule="evenodd"
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        />
                    </svg>
                    Add category
                    </button>
                </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">Category name</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(category, index) in categories" :key="category.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ category.name }}</th>
            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + category.id" :data-dropdown-toggle="'dropdown-' + category.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + category.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + category.id">
                        <li>
                            <a href="#" @click="openEditModal(category)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>

                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteCategory(category, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
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