<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

defineProps([
    "user"
])

//Thumbnail del usuario
const photo = ref();

//Variables del formulario
const category = ref();
const name = ref();
const description = ref();
const price = ref();
const brand = ref();
const discount = ref();
const stock = ref();

async function createProduct(event) {
    let base64 = photo.value

    let data = {
        "category": category.value,
        "name": name.value,
        "description": description.value,
        "price": price.value,
        "brand": brand.value,
        "discount": discount.value,
        "stock": stock.value,
        "thumbnail": base64
    }

    await axios.post('/uploadProduct', data)
        .then(res => console.log(res))
}

function changePhoto(ev) {
    let file = ev.target.files[0];
    let reader = new FileReader();

    reader.addEventListener("load", () => {
        photo.value = reader.result;
    }, false)
    
    if( file ) {
        reader.readAsDataURL(file);
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create new product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <form @submit.prevent="createProduct($event)" enctype="multipart/form-data" method="post" class="flex flex-col align-center justify-center content-center w-full">

                        <div class="flex flex-col items-center py-5">
                            <label for="category">Category</label>
                            <input class="w-1/2" type="text" v-model="category" name="category" id="category" placeholder="Insert Product Category">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="name">Name</label>
                            <input class="w-1/2" type="text" v-model="name"  id="name" name="name" placeholder="Insert Product Name">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="description">Description</label>
                            <textarea class="w-1/2" id="description" v-model="description"  name="description" placeholder="Insert Product Description"></textarea>
                        </div>  

                        <div class="flex flex-col items-center py-5">
                            <label for="price">Price</label>
                            <input class="w-1/2" type="number" id="price" v-model="price"  name="price" placeholder="Insert Product Price">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="brand">Brand</label>
                            <input class="w-1/2" type="text" name="brand" v-model="brand"  placeholder="Insert Product Brand">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="discount">Discount</label>
                            <input class="w-1/2" type="number" name="discount" v-model="discount"  placeholder="Insert Product Discount">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="stock">Stock</label>
                            <input class="w-1/2" type="number" name="stock" v-model="stock" placeholder="Insert Product Stock">
                        </div>

                        <div class="flex flex-col items-center py-5">
                            <label for="thumbnail">Thumbnail</label>
                            <img v-if="photo" :src="photo" width="400">
                            <input class="w-1/2 my-5" type="file" @change="changePhoto($event)" name="thumbnail" placeholder="Insert Product Thumbnail">
                        </div>

                        <button type="submit" value="Send" class="border my-5 bg-slate-300 rounded-full px-32 py-2 self-center hover:cursor-pointer hover:bg-slate-600 hover:text-white transition-all ease-in-out s">Send</button>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
