<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ImageInputVue from '@/Components/ImageInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    categories: Object
});

const form = useForm({
    title: '',
    description: '',
    rate: '',
    image: null,
    category_id: '',
});

const submit = () => {
    form.post(route('movie.store'), {
        onFinish: () => form.reset(),
    },{
        forceFormData: true,
    });
};


</script>

<template>
    <Head title="Movie" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Movie</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-24">
                    <h3>Create Movie</h3>
                    <hr />
                    <br />
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div>
                            <InputLabel for="title" value="title" />

                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        <div>
                            <InputLabel for="description" value="description" />

                            <TextInput
                                id="description"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                        <div>
                            <InputLabel for="rate" value="rate" />

                            <TextInput
                                id="rate"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.rate"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.rate" />
                        </div>
                        <InputLabel for="category_id" value="category" />
                        <select v-model="form.category_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option v-for="category in categories" :value="category.id">{{ category.title }}</option>
                        </select>
                        <div class="flex items-center mt-4 ">
                            <input type="file" @input="form.image = $event.target.files[0]" />

                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>


                        <div class="flex items-center justify-end mt-4 ">

                            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                save
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
