<script setup>
      import SideBarLayout from '@/Layouts/SideBarLayout.vue';
      import { Head, Link, useForm } from '@inertiajs/vue3';


      const props = defineProps({
        category: Array
      })

      const form = useForm({
          name: '',
          description: ''
      })

      const submit = ()=> {
        form.submit('post', '/categories', {
            onSuccess: ()=> {
                form.name = '',
                form.description = '',
                $('#createModal').modal('hide');
            }
        })
      }

      const delForm = useForm({
        id: null
    });
      
    const onDelete = () => {
        delForm.submit('delete', '/categories/' + delForm.id, 
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
</script>
<template>
    <main>
       <Head title="Category"/>
        <div class="mt-10  font-bold">
            <div
            v-if="$page.props.create.message"
            class="p-3 mt-4 text-md text-green-800 border-b-2 border-green-700 bg-green-300 rounded-lg"
            role="alert"
        >
            <span class="font-medium">
                {{ $page.props.create.message }}
            </span>
        </div>

        <div
            v-if="$page.props.delete.message"
            class="p-3 mt-4 text-md text-red-800 border-b-2 border-red-700 bg-red-300 rounded-lg"
            role="alert"
        >
            <span class="font-medium">
                {{ $page.props.delete.message }}
            </span>
        </div>


            <div class="border mt-3 p-2 border-gray bg-slate-200 shadow min-h-[50vh] rounded">
                <div class="flex gap-2 items-center">
                    <h1 class="text-3xl">All Categories</h1>
                    <!-- <input type="text" placeholder="Search" class="rounded border-none pl-5"  name="" id=""> -->
                    <Link class="bg-green-600 py-2 px-4 rounded text-lg text-white" data-bs-toggle="modal" data-bs-target="#createModal">Add</Link>
                </div>
                <div class="mt-3 overflow-auto  max-h-[50vh] ">
                    <table class="w-full">
                        <thead class="bg-gray-300  text-xl">
                            <tr>
                                <th class="w-[40%]">Categories</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                            <tr v-for="category in category" :key="category.id" class="border-b border-black">
                                <td class="p-3 text-lg">{{ category.name }}</td>
                                <td class="flex gap-3 items-center h-[10vh] ml-2">
                                    <!-- <button class="bg-blue-600 p-2 text-white rounded hover:scale-125 transition ease-linear duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button> -->
                                    <button class="bg-red-600 rounded p-2 text-white hover:scale-125 transition ease-linear duration-150" type="button" @click="openDeleteModal(category.id)"  data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    Are you sure you want to delete this category?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="onDelete()">Delete</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-3xl font-bold" id="exampleModalLabel">Create Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <form @submit.prevent="submit">
                                <div class="mb-2">
                                    <label for="name">Category Name</label>
                                    <input type="text" v-model="form.name" class="w-full rounded form-control" id="name">
                                </div>
                                <div class="mb-2">
                                    <label for="desc">Description</label>
                                    <textarea name="desc" v-model="form.description" id="desc" cols="30" class="w-full border-black rounded form-control" rows="10"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" :disabled="form.processing" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
   </main>
</template>