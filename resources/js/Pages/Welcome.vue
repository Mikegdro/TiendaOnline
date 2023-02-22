<script setup>
import { Head, Link } from '@inertiajs/vue3';

import ShoppingCart from '@/Components/ShoppingCart.vue';
import ComboBox from '@/Components/ComboBox.vue';

//Estos componentes son los que he usado para hacerlo todo asincrono
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

//Con el scaffolding nos vienen ya definidas las props, pero aquí nos llega la información que nos pasa laravel como parámetros
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    csrfToken: String,
});

//Para los productos
let products = null;
const orderBy = ref('name');
const orderType = ref('asc');
const render = ref(0);

//Para el carito
const renderCart = ref(0);
const cart = ref(false);
let productsInCart = [];
const total = ref(0);

//Para los filtros
const filters = ref([]);

//Hook que salta cuando la aplicación es montada y cargada para recoger datos
onMounted(async function () {
    sort('name');
})

//Función para forzar que se renderice de nuevo un componente
const reRender = () => {
    render.value += 1;
}

//Renderiza de nuevo el carrito
const refreshCart = () => {
    renderCart.value += 1;
}

//Función que llamará de manera asíncrona a la api para pedirle los datos con parámetros de ordenación y filtros
async function sort(order, type) {

    if( order ) {
        orderBy.value = order;
    } 

    if( type ) {
        orderType.value = type;
    }

    await axios.post('/getData', {
        orderby: orderBy.value,
        ordertype: orderType.value,
        filters: filters.value
    }).then(prods => products = prods.data)

    reRender();
}

//Añade al carrito
function addToCart(product) {

    productsInCart.push(product);

    let qty = parseFloat(product.price)

    total.value += qty;

    showCart();
}

//Muestra el carrito
function showCart() {
    cart.value = !cart.value;
}

//Elimina un item del carrito
function removeItem(product) {
    productsInCart.splice(productsInCart.indexOf(product), 1);

    total.value -= parseFloat(product.price);

    refreshCart();
}

//Aplica los filtros seleccionados
function applyFilter(event) {
    //Cogemos el tipo de elemento y el valor
    let type = event.target.id;
    let value = event.target.value;

    //Comprobamos si hay algun filtro del mismo tipo
    let hasFilter = filters.value.filter(filter => filter.type == type);

    //Si hay algun filtro del mismo tipo, se borra e intercarmbia
    if( hasFilter.length > 0 ) {
        console.log("Eliminando filtro");
        filters.value.splice(filters.value.indexOf(hasFilter), 1);
    }

    //Añadimos el filtro nuevo
    filters.value.push({type, value});

    //Llamamos a la función que ejecuta de nuevo la petición para pedir datos, ahora con filtros
    sort();
}


</script>

<template>
    <Head title="Welcome" />
 
    <ShoppingCart :key="renderCart" @remove="product => removeItem(product)" :show="cart" @close="showCart()" :products="productsInCart" :amount="total">

    </ShoppingCart>

    <div v-if="canLogin" class="opacity-90 bg-gray-800 w-full sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="font-semibold text-white hover:text-gray-900 dark:text-white dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Dashboard</Link
        >

        <template v-else>
            <Link
                :href="route('login')"
                class="font-semibold text-white hover:text-gray-400 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Log in</Link
            >

            <Link
                v-if="canRegister"
                :href="route('register')"
                class="ml-4 font-semibold text-white hover:text-gray-400 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Register</Link
            >
        </template>
    </div>

    
    <div class="flex sm:mt-20 mt-5 gap-5 px-5">
        <div class="basis-2/5 border-black border sm:px-5 flex flex-col pt-5">
            <h1 class="text-center py-2 text-xl">Brands</h1>
            <select id="brands" @change=" applyFilter($event)" class="mx-2">
                <option>All</option>
                <option>Apple</option>
                <option>Samsung</option>
                <option>Huawei</option>
                <option>HP</option>
                <option>Infinix</option>
            </select>
            
        </div>
        
        <div :key="render" class="basis-3/5 flex flex-wrap gap-10 justify-evenly">

            <div>
                <h1 class="text-center text-lg">Search</h1>
                <ComboBox />
            </div>
            
            
            <div class="w-full h-5 flex justify-evenly content-center items-center py-10 rounded-lg">

                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('name', 'asc')"> name &#x25b4;</a>
                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-else @click=" sort('name', 'desc')"> name &#x25be;</a>

                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('price', 'asc')"> price &#x25b4;</a>
                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-else @click=" sort('price', 'desc')"> price &#x25be;</a>

            </div>

            <div v-for="product in products"  class="rounded max-w-xs overflow-hidden shadow-xl " :key="product.id">
                <img class="w-full" :src="product.thumbnail" alt="Sunset in the mountains">
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{ product.name }}</div>
                    <p class="text-gray-700 text-base">
                        {{ product.description }}
                    </p>
                    <p class="text-xl mt-5 text-center"> {{ product.price }} €</p>
                </div>
                <div class="w-full flex justify-center items-center content-center">
                    <button @click="addToCart(product)"  class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xl font-semibold text-gray-700 mr-2 mb-2">
                        Add to Cart
                    </button>
                    <button class="inline-block bg-gray-200 rounded-full px-3 py-1 text-xl font-semibold text-gray-700 mr-2 mb-2">Buy Now</button>
                </div>
            </div>
        </div>
        
    </div>
    
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

</style>
