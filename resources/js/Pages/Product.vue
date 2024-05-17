<script setup>
    import SideBarLayout from '@/Layouts/SideBarLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import QRCode from 'qrcode';
    import Qrscanner from '../Components/Qrscanner.vue'

    const props = defineProps({
        product: Array,
        category: Array,
        errors: Object,
    })

    const form = useForm({
        name: '',
        description: '',
        quantity: '',
        status: '',
        category: '',
        price: '',
        image: ''
    })

    const submit = () => {
        form.submit('post', '/products', {
            onSuccess: ()=> {
                form.name = '',
                form.description = '',
                form.quantity =  '',
                form.status =  '',
                form.category = '',
                form.price = '',
                form.image = ''
                $('#createModal').modal('hide');
                generateQRCode();
            }
        })
    }

    const qrCodes = ref([]);

    const generateQRCode = async () => {
        const qrCodeUrls = await Promise.all(
            props.product.map(product =>
                QRCode.toDataURL(`Product ID: ${product.id},
                                Product Name: ${product.name},
                                Product Description: ${product.description},
                                Product Price: ${product.price}`)
            )
        );
        qrCodes.value = qrCodeUrls;
    };

    onMounted(generateQRCode);

    const editForm = useForm({
        name: '',
        description: '',
        quantity: '',
        status: '',
        category_name: '',
        price: '',
        image: ''
    })

    const openEditModal = (product) => {
        editForm.name = product.name;
        editForm.description = product.description;
        editForm.quantity = product.quantity;
        editForm.status = product.status;
        editForm.category_name = product.category_name;
        editForm.price = product.price;
        editForm.image = product.image;

        editForm.id = product.id;
    };

    const submitEditForm = () => {
        editForm.submit('put', `/products/${editForm.id}`,
        {
            preserveScroll: true,
            onSuccess: ()=> {   
                $('#editModal').modal('hide');
           
            }
        });
    };
        

    const delForm = useForm({
        id: null
    });
      
    const onDelete = () => {
        delForm.submit('delete', '/products/' + delForm.id, 
        {
            onSuccess: ()=> {
                $('#deleteModal').modal('hide');
            },
            preserveScroll: true,
        });
    };

    const openDeleteModal = (productId) => {
        delForm.id = productId;
    };

    defineOptions({
        layout: SideBarLayout
    })

    function dropDown(){
        document.querySelector('#submenu').classList.toggle('hidden')
        document.querySelector('#icon').classList.toggle('rotate-180')
    }

    //    const handleFileUpload = (event) => {
    //     form.file = event.target.files[0];
    // }

    // const importCSV = () => {
    //     const formData = new FormData();
    //     formData.append('file', form.file);

    //     form.post(route('products.import'), formData, {
    //         onSuccess: () => {
    //             $('#importModal').modal('hide');
    //         }
    //     });

    //     console.log(formData)
    // }
</script>
<template>
    <Head title="Product"/>
    
    <div class="md:flex items-center mt-2 sticky top-16 md:top-11">
        <div class="flex-1 flex mt-2 items-center gap-2">
            <h1 class="text-4xl font-bold ">Product</h1>
        </div>

        <div class="flex items-center">
            <div class="mr-44 mt-3">
                <button type="button" class="bg-blue-500 font-bold text-white p-2 rounded "  data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
            </div>
            <div class="flex flex-col items-center absolute right-0 top-3">
                <div class="flex items-center gap-2 border-b-2 border-black h-10 border-t-2 p-2 rounded-tr-md rounded-tl-md cursor-pointer" @click="dropDown()" >
                    <h1 class="text-lg tracking-wide">Select Options</h1>
                    <span id="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                        </svg>
                    </span>
                </div>
                <div class="flex flex-col text-start z-50 relative bg-gray-200 w-full hidden"  id="submenu">
                    <a href="/products/pdf" class="p-2 text-black hover:bg-gray-300 transition ease-linear duration-150">
                        Export PDF
                    </a>
                    <a href="/dowload/csv" class="hover:bg-gray-300 p-2 transition ease-linear duration-150">
                            Export CSV     
                    </a>
                    <button class="hover:bg-gray-300 p-2 transition ease-linear duration-150 text-start" data-bs-toggle="modal" data-bs-target="#Qrscanner">
                        QrScanner
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        v-if="$page.props.create.message"
        class="p-3 -mb-9 text-md text-green-800  border-b-2 rounded mt-3 border-green-700 bg-green-300"
        role="alert"
    >
        <span class="font-medium">
            {{ $page.props.create.message }}
        </span>
    </div>

    <div
        v-if="$page.props.update.message"
        class="p-3 -mb-9 text-md text-green-800  border-b-2 rounded mt-3 border-green-700 bg-green-300"
        role="alert"
    >
        <span class="font-medium">
            {{ $page.props.update.message }}
        </span>
    </div>

    <div
        v-if="$page.props.delete.message"
        class="p-3 -mb-9 text-md text-red-800  border-b-2 rounded mt-3 border-red-700 bg-red-300"
        role="alert"
    >
        <span class="font-medium">
            {{ $page.props.delete.message }}
        </span>
    </div>

    <div class="border border-black  mt-5 h-[60vh] overflow-y-auto md:overflow-x-hidden rounded">
        
        <table class="w-full table-auto">
             <!-- if you need  the thead to be fixed you can use sticky top-0 in order for it to be fixed
                i remove the sticky part for dropdown list --'z-index'-- purpose
            -->
            <thead class="border-b-2 w-full text-lg font-bold bg-black text-white border-gray-300">  
                <tr>
                    <th class="w-[10%] p-2">Image</th>
                    <th class="p-2 w-[15%]">Product</th>
                    <th class="w-[20%]">Category</th>
                    <th class="w-[10%]">Quantity</th>
                    <th class="w-[10%]">Price</th>
                    <th class="w-[10%]">Status</th>
                    <th>Qrcode</th>
                    <th class="w-[20%] text-center">Action</th>
                </tr>
            </thead>
            <tbody  class="mb-5">
                <tr  v-for="(product, index) in product" :key="product.id" class="border-b border-gray-300">
                    <td class="whitespace-nowrap p-2">
                        <img :src="product.image" class="w-14 h-12 rounded" alt="image">
                    </td>
                    <td class="p-3">{{ product.name }}</td>
                    <td>{{ product.category_name }}</td>
                    <td class="w-[20%]">
                        <div v-if="product.quantity == 0" class="text-red-500 font-bold">
                            {{ product.quantity }}
                        </div>
                        <div v-else>
                            {{ product.quantity }}
                        </div>
                    </td>
                    <td class="w-[15%]">{{ product.price }}</td>
                    <td class="w-[15%]">
                        <div v-if="product.quantity > 0" class="bg-green-700 font-bold w-[60%] text-center text-white p-2 rounded">
                            Available
                        </div>
                        <div v-else class="bg-red-700 font-bold w-[60%] text-center text-white p-2 rounded">
                            Sold out
                        </div>
                    </td>
                    <td class="w-[20%] text-center">
                        <img :src="qrCodes[index]" alt="QR Code" style="max-width: 80px; max-height: 80px;">
                    </td>
                    <td class="w-[20%] text-center">
                        <div class="flex justify-center items-center ml-9 gap-2">
                            <button  @click="openEditModal(product)" data-bs-toggle="modal" data-bs-target="#editModal" class="p-2 bg-green-500 text-white rounded hover:scale-125 transition ease-linear duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button class="p-2 bg-red-500 mr-10 rounded hover:scale-125 transition ease-linear duration-150 text-white" type="button" @click="openDeleteModal(product.id)"  data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </td>    
                </tr>
            </tbody>
        </table>
    </div>
<!-- 
    <div class="mt-3 text-xl border border-gray-500 w-[30%] p-2">
        <h1>Import file</h1>

        <button class="p-2 mt-2 bg-yellow-500 text-white rounded" data-bs-toggle="modal" data-bs-target="#importModal">Import Csv</button>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importLabel">Import CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        Sir mo error siya nig submit pero mo gana siya pero need eh refresh ang page
                    </div>
                    <input type="file" @change="handleFileUpload" accept=".csv" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="importCSV">Import</button>
                </div>
            </div>
        </div>
    </div> -->


    <div class="modal fade" id="Qrscanner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Qr Code Scanner</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div>
                    <Qrscanner />
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-3xl font-bold" id="exampleModalLabel">Create Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <form @submit.prevent="submit">
                            <div class="mb-2">
                                <label for="name" class="text-lg font-bold">Product Name</label>
                                <input type="text" v-model="form.name" class="form-control w-full rounded" id="name">
                                <div class="text-red-700" v-if="errors.name">{{ errors.name }}</div>
                            </div>
                            <div class="mb-2">
                                <label for="description" class="text-lg font-bold">Description</label>
                                <textarea name="description" id="description" v-model="form.description" class="form-control border-black w-full rounded" cols="10" rows="5"></textarea>
                                <div class="text-red-700" v-if="errors.description">{{ errors.description }}</div>
                            </div>
                            <div class="mb-2">
                                <label for="category" class="text-lg font-bold">Category</label>
                                <select v-model="form.category" id="category" class="form-control w-full border-black rounded">
                                    <option value="" selected disabled>Select Category</option>
                                    <option v-for="cat in category" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
                                </select>
                                <div class="text-red-700" v-if="errors.category">{{ errors.category }}</div>
                            </div>
                            <div class="flex gap-4 w-full">
                                <div class="mb-2 w-full">
                                    <label for="quantity" class="text-lg font-bold">Quantity</label>
                                    <input type="number" placeholder="0" step="0.05" v-model="form.quantity" class="form-control w-full rounded" id="quantity">
                                    <div class="text-red-700" v-if="errors.quantity">{{ errors.quantity }}</div>
                                </div>
                                <div class="mb-2 w-full">
                                    <label for="price" class="text-lg font-bold">Price</label>
                                    <input type="number" placeholder="P 0.00" v-model="form.price" step="0.05" class="form-control w-full rounded" id="price">
                                    <div class="text-red-700" v-if="errors.price">{{ errors.price }}</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="status" class="text-lg font-bold">Status</label>
                                <select name="status" id="status"  v-model="form.status" class="form-control border-black w-full rounded">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Sold Out">Sold Out</option>
                                </select>

                                <div class="text-red-700" v-if="errors.status">{{ errors.status }}</div>
                            </div>
                            <div class="mb-2">
                                <label for="image" class="text-lg font-bold w-full">Image</label>
                                <input type="file" id="image" name="image" @input="form.image = $event.target.files[0]">
                                <div class="text-red-700" v-if="errors.image">{{ errors.image }}</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="onDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-3xl font-bold" id="exampleModalLabel">Create Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <form @submit.prevent="submitEditForm">
                            <div class="mb-2">
                                <label for="name" class="text-lg font-bold">Product Name</label>
                                <input type="text" v-model="editForm.name" class="form-control w-full rounded" id="name">
                            
                            </div>
                            <div class="mb-2">
                                <label for="description" class="text-lg font-bold">Description</label>
                                <textarea name="description" id="description" v-model="editForm.description" class="form-control border-black w-full rounded" cols="10" rows="5"></textarea>
                               
                            </div>
                            <div class="mb-2">
                                <label for="category" class="text-lg font-bold">Category</label>
                                <select v-model="editForm.category_name" id="category" class="form-control w-full border-black rounded">
                                    <option value="" selected disabled>Select Category</option>
                                    <option v-for="cat in category" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
                                </select>
                              
                            </div>
                            <div class="flex gap-4 w-full">
                                <div class="mb-2 w-full">
                                    <label for="quantity" class="text-lg font-bold">Quantity</label>
                                    <input type="number" placeholder="0" step="0.05" v-model="editForm.quantity" class="form-control w-full rounded" id="quantity">
                                  
                                </div>
                                <div class="mb-2 w-full">
                                    <label for="price" class="text-lg font-bold">Price</label>
                                    <input type="number" placeholder="P 0.00" v-model="editForm.price" step="0.05" class="form-control w-full rounded" id="price">
                                
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="status" class="text-lg font-bold">Status</label>
                                <select name="status" id="status"  v-model="editForm.status" class="form-control border-black w-full rounded">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Sold Out">Sold Out</option>
                                </select>

                              
                            </div>
                            <div class="mb-2">
                                <div>
                                    <img :src="editForm.image" alt="" class="h-32 w-32">
                                </div>
                                <label for="image" class="text-lg font-bold w-full">Image</label>
                                <input type="file" id="image" name="image" @input="editForm.image = $event.target.files[0]">
                                <div class="text-red-700" v-if="errors.image">{{ errors.image }}</div>
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" >Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
            
</template>
