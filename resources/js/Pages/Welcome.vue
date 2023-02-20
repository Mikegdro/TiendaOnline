<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShoppingCart from '@/Components/ShoppingCart.vue';

//Estos componentes son los que he usado para hacerlo todo asincrono
import { ref, onMounted, nextTick } from 'vue';

//Con el scaffolding nos vienen ya definidas las props, pero aquí nos llega la información que nos pasa laravel como parámetros
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
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

//Para la búsqueda
const suggestions = ref([
    {
        name: "Samsung",
        price: 1799
    }
]);

let search = "";


//Hook que salta cuando la aplicación es montada y cargada para recoger datos
onMounted(async function () {
    products = await fetch('/api/getData');

    products = await products.json();

    reRender();
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
async function sort(order) {

    if( orderType.value == 'asc' ) {
        orderType.value = 'desc'
    } else {
        orderType.value = 'asc'
    }

    orderBy.value = order;

    let headers = new Headers();
    headers.append("accept", "application/json");
    headers.append("Content-type", "application/json");

    let raw = JSON.stringify({
        orderby: order,
        ordertype: orderType.value,
        filters: filters.value
    });

    let requestOptions = {
        method: 'POST',
        headers: headers,
        body: raw
    }

    await fetch('/api/order', requestOptions)
        .then(response => response.json())
        .then(result => products = result)
        .catch(error => console.log('Error: ', error));

    console.log(products)

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
    filters.value.push(event.target.value);
    sort(orderBy.value);
}

//Recoge las sugerencias con respecto a la búsqueda
function getSuggestions() {
    /**
     * Aquí habría que hacer un fetch para recoger los datos dependiendo del estado de 
     * la variable @search y pasandole también el orderby actual y el ordertype, pero
     * esos datos solo se usaría para mostrar sugerencias por lo que se deben de 
     * almacenar en otra variable
     * 
     */
    
}

</script>

<template>
    <Head title="Welcome" />
 
    <ShoppingCart :key="renderCart" @remove="product => removeItem(product)" :show="cart" @close="showCart()" :products="productsInCart" :amount="total">

    </ShoppingCart>

    <div
        class="relative sm:flex sm:justify-center sm:items-center bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="opacity-90 bg-teal-800 w-full sm:fixed sm:top-0 sm:right-0 p-6 text-right">
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

        
    </div>
    
    <div class="flex sm:mt-20 mt-5 gap-5 px-5">
        <div class="basis-2/5 border-black border-2 rounded-lg sm:px-5 flex flex-col pt-5">
            <h1 class="text-center py-2 text-xl">Brand</h1>
            <select @change=" applyFilter($event)" class="">
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
                <h1 class="text-xl text-center">Search</h1>
                <input v-model="search" @input="getSuggestions()" type="text" class="mt-3 border-b-slate-500 border-b-2 border-t-0 border-l-0 border-r-0 focus:ring-0">
                <div v-for="suggestion in suggestions" class="text-center mt-5 border-slate-800 border-2 rounded-full p-2 cursor-pointer">
                    <p>{{ suggestion.name }} &nbsp;-&nbsp; {{ suggestion.price }} €</p>
                </div>
            </div>
            
            
            <div class="w-full h-5 flex justify-evenly content-center items-center py-10 rounded-lg">
                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('name')"> name &#x25b4;</a>
                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-else @click=" sort('name')"> name &#x25be;</a>

                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-if=" orderType == 'desc' " @click=" sort('price')"> price &#x25b4;</a>
                <a class="cursor-pointer border-2 border-solid border-black px-5 rounded-full" v-else @click=" sort('price')"> price &#x25be;</a>

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
