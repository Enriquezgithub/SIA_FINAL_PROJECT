<script setup>
    import SideBarLayout from '@/Layouts/SideBarLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        customer: Array
    })

    const form = useForm({
        name: '',
        address: '',
        phone: '',
        email: '',
        file: null
    })

    const submit = () => {
        form.submit('post', '/customers', {
            onSuccess: () => {
                $('#create').modal('hide');
                form.reset();
            }
        })
    }

    defineOptions({
        layout: SideBarLayout
    })

    const handleFileUpload = (event) => {
        form.file = event.target.files[0];
    }

    const importCSV = () => {
        const formData = new FormData();
        formData.append('file', form.file);

        form.post(route('customers.import'), formData, {
            onSuccess: () => {
                $('#importModal').modal('hide');
            }
        });
    }
</script>
<template>
    <main>
       <Head title="Customer"/>
        <div class="flex items-centers mt-4">
            <h1 class="flex-1 text-4xl font-bold">Customer</h1>
            <div class="flex gap-2">
                <button class="p-2 bg-blue-500 text-white rounded"  data-bs-toggle="modal" data-bs-target="#create">Create Customer</button>
                <a href="/customers/export" class="p-2 bg-green-500 text-white rounded">Export Csv</a>
            </div>
        </div>

        <div v-if="$page.props.create.message" role="alert"  class="p-3 -mb-9 text-md text-green-800  border-b-2 rounded mt-3 border-green-700 bg-green-300">
            {{ $page.props.create.message }}
        </div>

        <div class="min-h-[30vh] p-2 rounded-md border border-gray-400 mt-5">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customer" :key="customer.id">
                        <td>{{ customer.id }}</td>
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.address }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>{{ customer.email }}</td>   
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-xl border border-gray-500 w-[30%] p-2">
            <h1>Import file</h1>

            <button class="p-2 mt-2 bg-yellow-500 text-white rounded" data-bs-toggle="modal" data-bs-target="#importModal">Import Csv</button>
            <!-- <input type="file" @change="handleFileUpload" accept=".csv" class="mt-2"> -->
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
                            Sir mo error siya nig submit pero mo gana siya need ra og refresh
                        </div>
                        <input type="file" @change="handleFileUpload" accept=".csv" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="importCSV">Import</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <form @submit.prevent="submit">
                            <div class="mb-2">
                                <label for="name">Name</label>
                                <input type="text" v-model="form.name" id="name" name="name" class="w-full rounded-md">
                            </div>
                            <div class="mb-2">
                                <label for="address">Address</label>
                                <input type="text" v-model="form.address" id="address" name="address" class="w-full rounded-md">
                            </div>
                            <div class="mb-2">
                                <label for="phone">Phone</label>
                                <input type="text" v-model="form.phone" id="phone" name="phone" class="w-full rounded-md">
                            </div>
                            <div class="mb-2">
                                <label for="email">Email</label>
                                <input type="email" v-model="form.email" id="email" name="email" class="w-full rounded-md">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </main>
</template>