<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
// import VueSweetalert2 from 'vue-sweetalert2';


defineProps({
    menu: Array
})


const menus = ref(usePage().props.menu);

const selectedMenu = ref(null)
const showMenuDialog = ref(false)

const isAddMenu = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);



// menu data
const id = ref('');
const restaurant_id = ref('');
const name = ref('');
const description = ref('');
const price = ref('');
const isSpecial = ref(false);


// Open the add modal
const openAddModal = () => {
    resetFormData();
    isAddMenu.value = true;
    dialogVisible.value = true;
    editMode.value = false;
    
}  
const AddMenu = async () => {
  const formData = new FormData();
  formData.append('restaurant_id', restaurant_id.value);
  formData.append('name', name.value);
  formData.append('description', description.value);
  formData.append('price', price.value);
  formData.append('isSpecial', isSpecial.value ? 1 : 0);

  try {
    await router.post('menu/store', formData, {
      onSuccess: (page) => {
        Swal.fire({
          toast: true,
          icon: 'success',
          position: 'top-end',
          showConfirmButton: false,
          title: page.props.flash?.success || 'Menu item added successfully!',
        });
        dialogVisible.value = false;
        resetFormData();
        router.visit('/restaurant-owner/menu');
      },
    });
  } catch (err) {
    console.error(err);
  }
};




// reset data after adding menu
const resetFormData = () => {
    id.value = '';
    restaurant_id.value = '';
    name.value = '';
    description.value = '';
    price.value = '';
    isSpecial.value = false;
}

const openEditModal = (menu) => {
    editMode.value = true;
    dialogVisible.value = true;
    isAddMenu.value = false;

    // update menu data
    id.value = menu.id;
    restaurant_id.value = menu.restaurant_id;
    name.value = menu.name;
    description.value = menu.description;
    price.value = menu.price;
    isSpecial.value = menu.isSpecial ? true : false;
}



// update menu
const updateMenu = async () => {
    const formData = new FormData();
    formData.append('restaurant_id', restaurant_id.value);
    formData.append('name', name.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('isSpecial', isSpecial.value ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        await router.post('menu/update/' + id.value, formData, {
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
        console.error(error);
    }
};


// delete menu
const deleteMenu = async (menu, index) => {
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
                router.delete('menu/destroy/' + menu.id, {
                    onSuccess: (page) => {
                        // remove item from array if you have it in list
                        menus.value.splice(index, 1);

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


const openMenuDetails = async (id) => {
    try {
        const response = await axios.get(`/restaurant/menu/show-data/${id}`)
        selectedMenu.value = response.data.menu
        showMenuDialog.value = true
    } catch (error) {
        console.error('Failed to load menu data:', error)
    }
}

</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <!-- dialog for adding or editing menu -->
        <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit menu item' : 'Add menu item'"
            width="500"
            :before-close="handleClose"
        >
            <!-- form start -->
            <form @submit.prevent="editMode ? updateMenu() : AddMenu()" class="max-w-md mx-auto">
                
                <!-- Restaurant ID (hidden or select if needed) -->
                <input v-model="restaurant_id" type="hidden">

                <!-- Name -->
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="name" type="text" id="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:text-blue-600">Name</label>
                </div>

                <!-- Description -->
                <div class="relative z-0 w-full mb-5 group">
                    <textarea v-model="description" id="description" rows="3" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required></textarea>
                    <label for="description" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:text-blue-600">Description</label>
                </div>

                <!-- Price -->
                <div class="relative z-0 w-full mb-5 group">
                    <input v-model="price" type="number" step="0.1" min="0" id="price" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="price" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:text-blue-600">
                        Price
                    </label>
                </div>


                <!-- isSpecial Checkbox -->
                <div class="flex items-center mb-5">
                    <input v-model="isSpecial" type="checkbox" id="isSpecial" class="mr-2">
                    <label for="isSpecial" class="text-sm text-gray-700">Special Menu Item</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </form>
            <!-- form end -->
        
        </el-dialog>

        <el-dialog
            v-model="showMenuDialog"
            title="Menu Details"
            width="400"
            :before-close="() => showMenuDialog = false"
        >
            <div v-if="selectedMenu">
                <p><strong>Name:</strong> {{ selectedMenu.name }}</p>
                <p><strong>Description:</strong> {{ selectedMenu.description }}</p>
                <p><strong>Price:</strong> €{{ selectedMenu.price }}</p>
                <p><strong>Special:</strong> {{ selectedMenu.isSpecial ? 'Yes' : 'No' }}</p>
                <p><strong>Created at:</strong> {{ new Date(selectedMenu.created_at).toLocaleString() }}</p>
                <p><strong>Updated at:</strong> {{ new Date(selectedMenu.updated_at).toLocaleString() }}</p>
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
                            <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search" required>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button type="button" @click="openAddModal" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add menu item
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">Item Name</th>
            <th scope="col" class="px-4 py-3">Description</th>
            <th scope="col" class="px-4 py-3">Price</th>
            <th scope="col" class="px-4 py-3">Special</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(menu, index) in menus" :key="menu.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ menu.name }}
            </th>
            <td class="px-4 py-3">{{ menu.description }}</td>
            <td class="px-4 py-3">€{{ menu.price }}</td>
            <td class="px-4 py-3">
                <span v-if="menu.isSpecial == 1" class="text-green-500 font-semibold">Yes</span>
                <span v-else class="text-red-500 font-semibold">No</span>
            </td>
            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + menu.id" :data-dropdown-toggle="'dropdown-' + menu.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + menu.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + menu.id">
                        <li>
                            <a href="#" @click.prevent="openMenuDetails(menu.id)" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a>
                        </li>
                        <li>
                            <a href="#" @click="openEditModal(menu)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteMenu(menu, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

            </div>
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px">
                    <li>
                        <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Previous</span>
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                    </li>
                    <li>
                        <a href="#" aria-current="page" class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Next</span>
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    </section>
</template>