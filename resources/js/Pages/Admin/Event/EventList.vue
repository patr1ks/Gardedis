<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { Plus } from '@element-plus/icons-vue';
import { onMounted, nextTick } from 'vue';
import { Datepicker } from 'flowbite';
import 'flowbite';
import { computed } from 'vue';
import axios from 'axios';


defineProps({
    events: Array
})

const restaurants = usePage().props.restaurants;
const events = usePage().props.events;

const selectedEvent = ref(null)

const isAddEvent = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);

// upload image
const eventImages = ref([]);
const dialogImageUrl = ref('');
const handleFileChange = (file, fileList) => {
    eventImages.value = fileList.map(f => ({
        name: f.name,
        url: f.url,
        raw: f.raw || f,
    }));
};


const handlePictureCardPreview = (file) => {
  dialogImageUrl.value = file.url
  dialogVisible.value = true
}

const handleRemove = (file) => {
  console.log(file)
}

// event data
const id = ref('');
const restaurant_id = ref('');
const title = ref('');
const description = ref('');
const event_date = ref('');
const event_images = ref([]);

// Open the add modal
const openAddModal = () => {
    resetFormData();
    isAddEvent.value = true;
    dialogVisible.value = true;
    editMode.value = false;
    
}  
const AddEvent = async () => {
    const formData = new FormData();
    formData.append('restaurant_id', restaurant_id.value);
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('event_date', event_date.value);


    // Append images correctly
    for (const image of eventImages.value) {
        if (image.raw) {
            formData.append('event_images[]', image.raw);
        }
    }

    try {
        await router.post('events/store', formData, {
            // headers: {
            //     'Content-Type': 'multipart/form-data'
            // },
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash?.success || 'Event added successfully!',
                });
                dialogVisible.value = false;
                resetFormData();
                router.visit('/admin/events');
            },
        });
    } catch (err) {
        console.error(err);
    }
};


// reset data after adding event
const resetFormData = () => {
    id.value = '';
    restaurant_id.value = '';
    title.value = '';
    description.value = '';
    eventImages.value = [];
    dialogImageUrl.value = '';
}

const openEditModal = (event) => {
    // console.log(event);
    editMode.value = true;
    dialogVisible.value = true;
    isAddEvent.value = false;

    //update data
    id.value = event.id;
    restaurant_id.value = event.restaurant_id;
    title.value = event.title;
    description.value = event.description;
    event_date.value = event.event_date;
    event_images.value = event.event_images;

}

//delete image
const deleteImage = async (eimage, index) => {
    try {
        await router.delete('/admin/events/image/'+eimage.id, {
            onSuccess: (page) => {
                event_images.value.splice(index, 1);
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

// update event
const updateEvent = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('event_date', event_date.value);
    formData.append('restaurant_id', restaurant_id.value);
    formData.append('_method', 'PUT');

    for (const image of eventImages.value) {
        formData.append('event_images[]', image.raw);
    }

    try {
        await router.post('events/' + id.value, formData, {
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

//delete event
const deleteEvent = async (event) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/admin/events/destroy/${event.id}`);

                // Find index based on event id
                const idx = events.findIndex(e => e.id === event.id);
                if (idx !== -1) {
                    events.splice(idx, 1);
                }

                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    title: 'Event deleted successfully!'
                });
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to delete event',
                    text: error.response?.data?.message || 'Something went wrong'
                });
            }
        }
    });
}

const searchQuery = ref('');

const filteredEvents = computed(() => {
  if (!searchQuery.value) return events;
  return events.filter(event =>
    event.restaurant?.title?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
            <!-- dialog for adding or editing event -->
            <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit event' : 'Add event'"
            width="500"
            :before-close="handleClose"
            class="dark:bg-gray-900 dark:text-white"
            >
            <div class="bg-white dark:bg-gray-800 dark:text-white p-4 rounded-lg">
                <form @submit.prevent="editMode ? updateEvent() : AddEvent()" class="max-w-md mx-auto">

                <!-- Restaurant Select -->
                <div class="relative z-0 w-full mb-5 group">
                    <label for="underline_select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select restaurant</label>
                    <select
                    id="underline_select"
                    v-model="restaurant_id"
                    class="block w-full text-sm rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                            focus:ring-blue-500 focus:border-blue-500
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                            py-2.5 px-3"
                    >
                    <option v-for="restaurant in restaurants" :key="restaurant.id" :value="restaurant.id">
                        {{ restaurant.title }}
                    </option>
                    </select>
                </div>

                <!-- Title -->
                <div class="relative z-0 w-full mb-5 group">
                    <input
                    v-model="title"
                    type="text"
                    id="floating_title"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none
                            dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                    required
                    />
                    <label for="floating_title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform
                            -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-blue-600 peer-focus:dark:text-blue-500
                            peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Title
                    </label>
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea
                    id="message"
                    v-model="description"
                    rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300
                            focus:ring-blue-500 focus:border-blue-500
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Write description"
                    ></textarea>
                </div>

                <!-- Date Picker -->
                <el-date-picker
                    v-model="event_date"
                    type="date"
                    placeholder="Select date"
                    format="DD.MM.YYYY"
                    value-format="YYYY-MM-DD"
                    class="w-full mt-4"
                />

                <!-- Upload -->
                <div class="relative z-0 w-full mb-5 group mt-5">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Images</label>
                    <el-upload
                        v-model:file-list="eventImages"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :on-change="handleFileChange"
                        accept="image/*"
                        :auto-upload="false"
                        multiple
                        :limit="10"
                        >
                        <el-icon><Plus /></el-icon>
                    </el-upload>
                </div>

                <!-- Existing images -->
                <div class="flex flex-nowrap mb-8">
                    <div v-for="(eimage, index) in event_images" :key="eimage.id" class="relative w-32 h-32">
                    <img class="w-24 h-24 rounded-sm" :src="`/${eimage.image}`" alt="" />
                    <span class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                        <span
                        @click="deleteImage(eimage, index)"
                        class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 cursor-pointer"
                        >
                        x
                        </span>
                    </span>
                    </div>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300
                        font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
                </form>
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
                            <input type="text" v-model="searchQuery" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search by restaurant"/>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button type="button" @click="openAddModal" class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add event
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-4 py-3">Restaurant name</th>
            <th scope="col" class="px-4 py-3">Title</th>
            <th scope="col" class="px-4 py-3">Description</th>
            <th scope="col" class="px-4 py-3 w-1/6">Event date</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="event in filteredEvents" :key="event.id" class="border-b dark:border-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ event.restaurant.title }}</th>
            <td class="px-4 py-3">{{event.title}}</td>
            <td class="px-4 py-3">{{event.description}}</td>
            <td class="px-4 py-3 w-1/6">{{event.event_date}}</td> 
            <td class="px-4 py-3 flex items-center justify-end">
                <button :id="'dropdown-button-' + event.id" :data-dropdown-toggle="'dropdown-' + event.id"
                    class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" 
                    type="button">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
                <div :id="'dropdown-' + event.id" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="'dropdown-button-' + event.id">
                        <li>
                            <a href="#" @click="openEditModal(event)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Edit</a>

                        </li>
                    </ul>
                    <div class="py-1">
                        <a href="#" @click="deleteEvent(event, index)" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
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