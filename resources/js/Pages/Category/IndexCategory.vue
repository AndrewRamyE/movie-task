<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import PagenateVue from '@/Components/Pagenate.vue';
import Swal from 'sweetalert2'
defineProps({
    categories: Object
});
const DeleteItem = (ItemId)=>{
    Swal.fire({
            title: 'DELET',
            text:   'Are you sure want to delete ?',
            icon: 'warning',
            showCloseButton: true,
            showCancelButton: true,

        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            axios.delete('/category/'+ItemId,{},{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
                .then(function(response){
                    Swal.fire('Saved!', '', 'success');
                    window.location.reload();
                })
                .catch(function(error){
                    Swal.fire('error!', '', 'error')
                });

        }
        });
}
</script>
<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <!-- component -->
        <section class="antialiased bg-gray-100 text-gray-600 m-5">
            <div class="row w-100 d-flex flex-row-reverse">
                <a class="btn btn-primary  w-20" :href="route('category.create')">Create</a>
                </div>
                <div class="flex justify-center pt-2">
                    <!-- Table -->
                    <div class="w-full mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
                        <header class="px-2 py-1 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-800">Category</h2>
                        </header>
                        <div class="p-3">
                            <div class="overflow-x-auto">
                                <table class="table-auto w-full">
                                    <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                        <tr>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Name</div>
                                            </th>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Action</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm divide-y divide-gray-100" v-for="item in categories.data">
                                        <tr :key="item.id">
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="font-medium text-gray-800">{{ item.title }}</div>
                                                </div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="text-left">
                                                    <a  :href="route('category.edit' ,item.id )" class="text-center block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-32" >Edit</a>
                                                    <button type="button" v-on:click="DeleteItem(item.id)" class="block bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-32">delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <PagenateVue :links="categories.links" :currentPage="categories.current_page"></PagenateVue>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </AuthenticatedLayout>
</template>
