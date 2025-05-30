<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { Plus } from '@element-plus/icons-vue';
import axios from 'axios';

defineProps({
    restaurants: Array
})

const categories = usePage().props.categories;
const restaurant = usePage().props.restaurant;
const user = usePage().props.user;

const dialogVisible = ref(false);
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
const category_ids = ref([]);

// delete image
const deleteImage = async (rimage, index) => {
    try {
        await router.delete('/restaurant-owner/restaurant/image/'+rimage.id, {
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
        })
    } catch (error) {
        console.log(error);
    }
}

// update restaurant
const updateRestaurant = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('_method', 'PUT');

    for (const id of category_ids.value) {
        formData.append('category_ids[]', id);
    }

    for (const image of restaurantImages.value) {
        formData.append('restaurant_images[]', image.raw);
    }

    try {
        await router.post(`/restaurant-owner/restaurant/update/${id.value}`, formData, {
            onSuccess: (page) => {
                const updated = page.props.restaurant;

                title.value = updated.title;
                price.value = updated.price;
                description.value = updated.description;
                category_ids.value = updated.category_ids;
                restaurant_images.value = updated.restaurant_images;

                lastSavedData.value = {
                    title: updated.title,
                    price: updated.price,
                    description: updated.description,
                    category_ids: [...updated.category_ids],
                    images: updated.restaurant_images
                };

                restaurantImages.value = [];

                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                });

                readonlyMode.value = true;
            }
        });
    } catch (error) {
        console.error(error);
    }
};

const lastSavedData = ref({
    title: '',
    price: '',
    description: '',
    category_ids: [],
    images: []
});

onMounted(() => {
    if (restaurant) {
        id.value = restaurant.id;
        title.value = restaurant.title;
        price.value = restaurant.price;
        description.value = restaurant.description;
        category_ids.value = restaurant.category_ids || [];
        restaurant_images.value = restaurant.restaurant_images;

        lastSavedData.value = {
            title: restaurant.title,
            price: restaurant.price,
            description: restaurant.description,
            category_ids: [...restaurant.category_ids],
            images: restaurant.restaurant_images
        };
    }
});

const readonlyMode = ref(true);

const enterEditMode = () => {
    readonlyMode.value = false;
};

const cancelEdit = () => {
    title.value = lastSavedData.value.title;
    price.value = lastSavedData.value.price;
    description.value = lastSavedData.value.description;
    category_ids.value = [...lastSavedData.value.category_ids];
    restaurant_images.value = lastSavedData.value.images;
    restaurantImages.value = [];
    readonlyMode.value = true;
};

const saveChanges = async () => {
    await updateRestaurant();
    lastSavedData.value = {
        title: title.value,
        price: price.value,
        description: description.value,
        category_ids: [...category_ids.value]
    };
    readonlyMode.value = true;
};
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div v-if="restaurant" class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="overflow-x-auto pt-10 pb-10">
            <!-- form start -->
            <form @submit.prevent="updateRestaurant()" class="max-w-md mx-auto">
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

            <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
            </form>
            </div>
        </div>
    </div>

    <div v-else class="p-10 text-center">
        <p class="text-lg text-red-500 font-semibold">
            No restaurant assigned to {{ user.email }}
        </p>
    </div>
</section>
</template>

<style scoped>
.el-image-viewer__btn--rotate-left,
.el-image-viewer__btn--rotate-right {
  display: none !important;
}
</style>
