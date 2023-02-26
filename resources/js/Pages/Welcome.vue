<script setup>
import { Head, Link } from '@inertiajs/vue3';

import ShoppingCart from '@/Components/ShoppingCart.vue';
import ComboBox from '@/Components/ComboBox.vue';
import Pagination from '@/Components/Pagination.vue';
import Sidebar from '@/Components/Sidebar.vue';

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
    extrainfo: Object,
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

//Para las páginas
const pages = ref(0);
const currentPage = ref(1);
let totalItems = 0;
let itemsPerPage = 5;
let productsInPage = [];


function updatePages() {
    
    if( itemsPerPage <= 0 ) {
        itemsPerPage = 1;
    } else if ( itemsPerPage > totalItems ) {
        itemsPerPage = totalItems;
    }

    getProductsInPage()

    pages.value += 1;
    reRender();
}

function changePages(page) {
    currentPage.value = page;
    updatePages();
}

//Hook que salta cuando la aplicación es montada y cargada para recoger datos
onMounted(async function () {
    await sort('name');
})

//Función para forzar que se renderice de nuevo un componente
const reRender = () => {
    render.value += 1;
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



    totalItems = products.length > 0 ? products.length : 1;

    updatePages();
    reRender();
}

//Añade al carrito
function addToCart(product) {

    let prod = productsInCart.filter( prod => product == prod);

    if( prod ) {
        console.log(prod)
    }

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

    // refreshCart();
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

function getProductsInPage() {

    productsInPage = [];

    let i = currentPage.value == 1 ? 0 : (currentPage.value - 1) * itemsPerPage;

    //TODO

    //El numero del final de la página es currentPage * itemsPerPage
    //El numero del principio, es el final de la página anterior más 1 => (currentPage - 1) * itemsPerPage + 1
    
    for(i; i < itemsPerPage * currentPage.value; i ++) {
        if( products[i] ) {
            productsInPage.push(products[i]);
        }
    }

}

function test() {
    axios.get("/seed")
        .then(res => console.log(res))
}
</script>

<template>
    <Head title="Welcome" />

    <ShoppingCart :key="renderCart" @remove="product => removeItem(product)" :show="cart" @close="showCart()" :products="productsInCart" :amount="total">

    </ShoppingCart>
    
    <div v-if="extrainfo">
        {{ extrainfo }}
    </div>

    <div class="hero">

    </div>
    
    <div class="flex h-full overflow-x-hidden">
        <Sidebar>
        
        </Sidebar>
        
        <div class="flex flex-col h-screen justify-evenly px-12 overflow-x-hidden box-border">
            
            <div class="w-full h-5 flex justify-evenly content-center items-center py-10 rounded-lg">

                <a class="cursor-pointer border border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('name', 'asc')"> name &#x25b4;</a>
                <a class="cursor-pointer border border-solid border-black px-5 rounded-full" v-else @click=" sort('name', 'desc')"> name &#x25be;</a>

                <a class="cursor-pointer border border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('price', 'asc')"> price &#x25b4;</a>
                <a class="cursor-pointer border border-solid border-black px-5 rounded-full" v-else @click=" sort('price', 'desc')"> price &#x25be;</a>

            </div>

            <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div v-for="product in productsInPage" :key="render" class="flex flex-col bg-gray-200 ">
                    <img :src="product.thumbnail">
                    <div class="px-6 py-4 flex flex-col justify-evenly items-center content-center h-full">
                        <div class="font-bold text-xl mb-2">{{ product.name }}</div>
                        <p class="text-gray-700 text-base">
                            {{ product.description }}
                        </p>
                        <p class=" mt-5 text-center"> {{ product.price }} €</p>
                    </div>
                    <div class="w-full flex justify-center items-center content-center">
                        <button @click="addToCart(product)"  class="inline-block bg-gray-400 rounded-full px-3 py-1 font-semibold text-white mr-2 mb-2">
                            Add to Cart
                        </button>
                        <button class="inline-block bg-gray-400 rounded-full px-3 py-1 font-semibold text-white mr-2 mb-2">Buy Now</button>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
    <Pagination class="w-full my-14 mx-auto flex justify-center" @page="page => changePages(page)" :items-per-page="itemsPerPage" v-model="currentPage" :total-items="totalItems" :key="pages" />

    <br/>

    <div class="flex justify-center">
        <label for="pages">Items Per Page:</label>
        <input type="number" id="pages" v-model="itemsPerPage" :key="pages" min="1" v-bind:max="totalItems" @change="updatePages()"  class="text-center w-64 mb-4" />
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

.hero {
    height: 30vh;
    background-image: url('../../images/pic1.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}


</style>
