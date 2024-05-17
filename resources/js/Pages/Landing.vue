<script setup>
    import SideBarLayout from '@/Layouts/SideBarLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { ref, computed } from "vue";

    const props = defineProps({
        product: Array,
        category: Array
    });

    const selectedCategory = ref("");
    const products = ref(props.product); 

    const filteredProducts = computed(() => {

        if (selectedCategory.value === "") {
            return products.value;
        } else {
            return products.value.filter(
            (product) => product.category_name === selectedCategory.value
            );
        }
    });



    defineOptions({
        layout: SideBarLayout
    });
</script>

<template>
    <Head title="Dashboard"/>
    <main>
        <div class="md:grid grid-cols-1 md:grid-cols-3 md:gap-4" >  
            <div class="my-5 bg-green-400 border-green-600 shadow rounded border-l-4 w-full h-[20vh]">
                <div class="p-2 flex flex-col h-full">
                    <h1 class="text-3xl flex-1 text-white">Total Product</h1>
                    <span class="text-3xl mb-5 text-green-900">{{ product ? product.length : 0 }}</span>
                </div>
            </div>

            <div class="my-5  bg-blue-400 rounded border-blue-600 shadow border-l-4 w-full h-[20vh]">
                <div class="p-2 flex flex-col h-full">
                    <h1 class="text-3xl flex-1 text-white">Total Category</h1>
                    <span class="text-3xl mb-5 text-blue-900">{{ category ? category.length : 0 }}</span>
                </div>
            </div>
        </div>

        <div class="mb-2 text-lg tracking-wider font-bold">
            <h1>
                Available Stock
            </h1>
        </div>
        <div class="md:flex gap-4 block">

            <div class="border border-gray-500 h-[50vh] mb-3 md:mb-0 w-full md:w-[50%] overflow-auto">
                <table class="w-full">
                    <thead class="border-b-2 border-gray-300">
                        <tr>
                            <th class="p-2">Image</th>
                            <th>Product Name</th>
                            <th>Stock</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody v-if="product.length > 0" v-for="product in product">              
                            <tr :key="product.id" v-if="product.quantity > 0">
                                <td class="whitespace-nowrap p-2">
                                    <img :src="product.image" class="w-14 h-12 rounded" alt="image">
                                </td>
                                <td>{{ product.name }}</td>
                                <td>{{ product.quantity }}</td>
                                <td>{{ product.category_name }}</td>
                            </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="4"  class="text-center">No Product Available</td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>

            <div class="md:w-[50%] -mt-7 w-full">
                <h1 class="text-lg font-bold tracking-wide">
                    Top Sales
                </h1>
                <div class="border border-gray-500  p-2 gap-3 bg-slate-200 flex flex-wrap ">

                    <div class="flex gap-1">
                        <div
                            @click="selectedCategory = 'Laptop'"
                            :class="{ 'bg-gray-300': selectedCategory === 'Laptop' }"
                            class="cursor-pointer px-4 py-2 rounded-md hover:bg-gray-300 transition ease-linear duration-150"
                        >
                            Laptop
                        </div>
                        <div
                            @click="selectedCategory = 'Beverages'"
                            :class="{ 'bg-gray-300': selectedCategory === 'Beverages' }"
                            class="cursor-pointer px-4 py-2 rounded-md hover:bg-gray-300 transition ease-linear duration-150"
                        >
                            Beverages
                        </div>
                        <div
                            @click="selectedCategory = 'Electronic'"
                            :class="{ 'bg-gray-300': selectedCategory === 'Electronic' }"
                            class="cursor-pointer px-4 py-2 rounded-md hover:bg-gray-300 transition ease-linear duration-150"
                        >
                            Electronic
                        </div>
                        <div
                            @click="selectedCategory = 'Medicines'"
                            :class="{ 'bg-gray-300': selectedCategory === 'Medicines' }"
                            class="cursor-pointer px-4 py-2 rounded-md hover:bg-gray-300 transition ease-linear duration-150"
                        >
                            Medicines
                        </div>
                    </div>
                    <div class="border border-gray-500 p-2 gap-3 bg-slate-200 flex flex-wrap max-h-[45vh] overflow-y-auto">
                        <div v-for="product in filteredProducts" :key="product.id" class="bg-white rounded shadow-md">
                            <div class="border border-black rounded flex flex-col items-start w-full p-3 h-[20vh]">
                                <img :src="product.image" class="w-14 h-8 rounded mx-auto flex-1" alt="image" />
                                <div class="mt-2 font-bold text-md tracking-wide">
                                    {{ product.name }} {{ product.category_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
   
</template>
