<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import PagenateVue from '@/Components/Pagenate.vue';
import Swal from 'sweetalert2'
const props = defineProps({
    movies: Object
});
let moviesData = props.movies ;
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
            axios.delete('/movie/'+ItemId,{},{
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
const MakeSearch = (e)=>{
    axios.post('/movie/search',{
        'search':e.target.value
    },{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
                .then(function(response){
                    console.log(response.data);
                    moviesData = response.data ;
                })
                .catch(function(error){
                    console.log(error);
                });
}
</script>
<template>
    <Head title="movies" />

    <AuthenticatedLayout>
        <!-- component -->
        <section class="antialiased bg-gray-100 text-gray-600 m-5">
                <div class="flex justify-center pt-2">
                    <label >Search</label>
            <input type="search" name="search" @change="MakeSearch" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
            </div>
            </section>
            <section class="antialiased bg-gray-100 text-gray-600 m-5">
                <div class="flex justify-center pt-2">
                    <!-- Table -->
                    <div class="w-full mx-auto bg-white shadow-lg rounded-sm border border-gray-200">
                        <header class="px-2 py-1 border-b border-gray-100">
                            <h2 class="font-semibold text-gray-800">Movie</h2>
                        </header>
                        <div class="p-3">
                            <div class="overflow-x-auto">
                                <table class="table-auto w-full">
                                    <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                                        <tr>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Title</div>
                                            </th>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Description</div>
                                            </th>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Rate</div>
                                            </th>
                                            <th class="p-2 whitespace-nowrap">
                                                <div class="font-semibold text-left">Action</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm divide-y divide-gray-100" v-for="item in moviesData.data">
                                        <tr :key="item.id">
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="font-medium text-gray-800">{{ item.title }}</div>
                                                </div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="font-medium text-gray-800">{{ item.description }}</div>
                                                </div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="font-medium text-gray-800">{{ item.rate }}</div>
                                                </div>
                                            </td>
                                            <td class="p-2 whitespace-nowrap">
                                                <div class="text-left">
                                                    <a  :href="route('movie.edit' ,item.id )" class="text-center block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-32" >Edit</a>
                                                    <button type="button" class="block bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-32" v-on:click="DeleteItem(item.id)">delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <PagenateVue v-if="movies.data" :links="movies.links" :currentPage="movies.current_page"></PagenateVue>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </AuthenticatedLayout>
</template>
