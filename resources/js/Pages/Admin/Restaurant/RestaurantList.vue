<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Plus } from '@element-plus/icons-vue';
import axios from 'axios';
import { ElImage } from 'element-plus';

defineProps({
    restaurants: Array
});

const restaurants = usePage().props.restaurants;
const categories = usePage().props.categories;
const users = usePage().props.users;

const isAddRestaurant = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);
const showdialogVisible = ref(false);
const selectedRestaurant = ref(null);

const restaurantImages = ref([]);
const dialogImageUrl = ref('');
const handleFileChange = (file, fileList) => {
    restaurantImages.value = fileList.map(f => ({
        name: f.name,
        url: f.url,
        raw: f.raw || f,
    }));
};

const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url;
    dialogVisible.value = true;
};

const handleRemove = (file) => {
    console.log(file);
};

// restaurant data
const id = ref('');
const title = ref('');
const description = ref('');
const price = ref('');
const published = ref('');
const restaurant_images = ref([]);
const category_ids = ref([]); // <-- changed from single value to array
const owner = ref('');

const openAddModal = () => {
    resetFormData();
    isAddRestaurant.value = true;
    dialogVisible.value = true;
    editMode.value = false;
};

const AddRestaurant = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('owner', owner.value);
    category_ids.value.forEach(id => formData.append('category_ids[]', id));

    for (const image of restaurantImages.value) {
        if (image.raw) {
            formData.append('restaurant_images[]', image.raw);
        }
    }

    try {
        await router.post('restaurants/store', formData, {
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash?.success || 'Restaurant added successfully!',
                });
                dialogVisible.value = false;
                resetFormData();
                router.visit('/admin/restaurants');
            },
        });
    } catch (err) {
        console.error(err);
    }
};

const resetFormData = () => {
    id.value = '';
    title.value = '';
    description.value = '';
    price.value = '';
    category_ids.value = []; // reset array
    owner.value = '';
    restaurantImages.value = [];
    dialogImageUrl.value = '';
};

const openEditModal = (restaurant) => {
    editMode.value = true;
    dialogVisible.value = true;
    isAddRestaurant.value = false;

    id.value = restaurant.id;
    title.value = restaurant.title;
    description.value = restaurant.description;
    price.value = restaurant.price;
    category_ids.value = restaurant.categories?.map(c => c.id) || []; // expects many-to-many
    owner.value = restaurant.owner;
    restaurant_images.value = restaurant.restaurant_images;
};

const deleteImage = async (rimage, index) => {
    try {
        await router.delete('/admin/restaurants/image/' + rimage.id, {
            onSuccess: (page) => {
                restaurant_images.value.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        });
    } catch (error) {
        console.log(error);
    }
};

const updateRestaurant = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('owner', owner.value);
    formData.append('_method', 'PUT');
    category_ids.value.forEach(id => formData.append('category_ids[]', id));

    for (const image of restaurantImages.value) {
        formData.append('restaurant_images[]', image.raw);
    }

    try {
        await router.post('restaurants/update/' + id.value, formData, {
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
        });
    } catch (error) {
        console.error(error);
    }
};

const deleteRestaurant = async (restaurant, index) => {
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
                router.delete('restaurants/destroy/' + restaurant.id, {
                    onSuccess: (page) => {
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success
                        });
                    }
                });
            } catch (error) {
                console.log(error);
            }
        }
    });
};

const openRestaurantDetails = async (id) => {
    try {
        const response = await axios.get(`/admin/restaurants/show-data/${id}`);
        selectedRestaurant.value = response.data.restaurant;
        categories.value = response.data.categories;
        user.value = response.data.user;
        showdialogVisible.value = true;
    } catch (error) {
        console.error('Failed to load restaurant data:', error);
    }
};
</script>


<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <!-- dialog for adding or editing restaurant -->
        <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit restaurant' : 'Add restaurant'"
            width="500"
            :before-close="handleClose"
        >
            <!-- form start -->
            <form @submit.prevent="editMode ? updateRestaurant():AddRestaurant()" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="title" type="text" name="floating_title" id="floating_title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input v-model="price" type="text" name="floating_price" id="floating_price" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="floating_price" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
            </div>

            <!-- Multi-category checkbox input -->
            <div class="relative z-0 w-full mb-5 group">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select categories</label>
            <div class="flex flex-wrap gap-3">
                <div v-for="category in categories" :key="category.id" class="flex items-center space-x-2">
                <input
                    type="checkbox"
                    :id="`category_${category.id}`"
                    :value="category.id"
                    v-model="category_ids"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                />
                <label :for="`category_${category.id}`" class="text-sm text-gray-900 dark:text-gray-300">{{ category.name }}</label>
                </div>
            </div>
            </div>


            <!-- owner (user) select -->
            <div class="relative z-0 w-full mb-5 group">
                <label for="owner_select">Select owner (email)</label>
                <select id="owner_select" v-model="owner" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.email }}</option>
                </select>
            </div>

            <div>
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                <textarea id="message" v-model="description" rows="4" class=" block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write description"></textarea>
            </div>

            <!-- image upload -->
            <div class="grid md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Images</label>
                    <el-upload
                        v-model:file-list="restaurantImages"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :on-change="handleFileChange"
                        accept="image/*"
                        :auto-upload="false"
                    >
                        <el-icon><Plus /></el-icon>
                    </el-upload>

                </div>
            </div>

            <!-- list of images for selected product -->
             <div class="flex flex-nowrap mb-8">
                <div v-for="(rimage, index) in restaurant_images" :key="rimage.id" class="relative w-32 h-32">
                    <img class="w-24 h-24 rounded-sm" :src="`/${rimage.image}`" alt="">
                    <span class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                        <span @click="deleteImage(rimage, index)" class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer">x

                        </span>
                    </span>
                </div>
             </div>

            <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
            <!-- form end -->
        
        </el-dialog>
        <!-- previed restaurant details -->
        <el-dialog
            v-model="showdialogVisible"
            title="Restaurant Details"
            width="500"
            :before-close="() => showdialogVisible = false"
        >
            <div v-if="selectedRestaurant">
                <p><strong>Title:</strong> {{ selectedRestaurant.title }}</p>
                <p><strong>Description:</strong> {{ selectedRestaurant.description }}</p>
                <p><strong>Price:</strong> {{ selectedRestaurant.price }}</p>
                <p><strong>Categories:</strong>
                <span v-if="selectedRestaurant.categories?.length">
                    <span v-for="(cat, index) in selectedRestaurant.categories" :key="cat.id">
                    {{ cat.name }}<span v-if="index < selectedRestaurant.categories.length - 1">, </span>
                    </span>
                </span>
                <span v-else>No categories</span>
                </p>

                <div v-if="selectedRestaurant?.restaurant_images?.length">
                    <p><strong>Images:</strong></p>
                    <el-image
                    v-for="image in selectedRestaurant.restaurant_images"
                    :key="image.id"
                    :src="'/' + image.image"
                    :preview-src-list="selectedRestaurant.restaurant_images.map(img => '/' + img.image)"
                    :initial-index="selectedRestaurant.restaurant_images.findIndex(i => i.id === image.id)"
                    style="width: 100px; margin-right: 10px; cursor: pointer"
                    fit="cover"
                    />
                </div>
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
                        Add restaurant
                    </button>
                    <div class="flex items-center space-x-3 w-full md:w-auto">
                        <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                            <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                            Actions
                        </button>
                        <div id="actionsDropdown" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mass Edit</a>
                                </li>
                            </ul>
                            <div class="py-1">
                                <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete all</a>
                            </div>
                        </div>
                        <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Filter
                            <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                        <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose brand</h6>
                            <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                <li class="flex items-center">
                                    <input id="apple" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="apple" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Apple (56)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="fitbit" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="fitbit" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Microsoft (16)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="razor" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="razor" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Razor (49)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="nikon" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="nikon" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nikon (12)</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="benq" type="checkbox" value="" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                    <label for="benq" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">BenQ (74)</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">Restaurant name</th>
            <th scope="col" class="px-4 py-3">Category</th>
            <th scope="col" class="px-4 py-3">Description</th>
            <th scope="col" class="px-4 py-3">Price</th>
            <th scope="col" class="px-4 py-3">Published</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="restaurant in restaurants" :key="restaurant.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ restaurant.title }}</th>
            <!-- <td class="px-4 py-3">{{restaurant.category.name}}</td> -->
            <td class="px-4 py-3">
            <span v-if="restaurant.categories && restaurant.categories.length">
                <span v-for="(cat, index) in restaurant.categories" :key="cat.id">
                {{ cat.name }}<span v-if="index < restaurant.categories.length - 1">, </span>
                </span>
            </span>
            <span v-else>No categories</span>
            </td>
            <td class="px-4 py-3">{{restaurant.description}}</td>
            <td class="px-4 py-3">{{restaurant.price}}</td> 
            <td class="px-4 py-3">
                <button v-if="restaurant.published == 0" type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Published</button>
                <button v-else type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Unpublished</button>
                </td>
            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + restaurant.id" :data-dropdown-toggle="'dropdown-' + restaurant.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + restaurant.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + restaurant.id">
                        <li>
                            <a href="#" @click="openRestaurantDetails(restaurant.id)" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a>
                        </li>
                        <li>
                            <!-- <button @click="openEditModal(restaurant)" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</button> -->
                            <a href="#" @click="openEditModal(restaurant)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>

                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteRestaurant(restaurant, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
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

<style scoped>
.el-image-viewer__btn--rotate-left,
.el-image-viewer__btn--rotate-right {
  display: none !important;
}
</style>