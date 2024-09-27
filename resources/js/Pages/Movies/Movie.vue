<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from 'primevue/card';


defineProps({
    movies: Array,
    rolePermissions: Array
});
</script>


<template>

    <Head title="Movies" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Movies
            </h2>

            
            <p class="text-gray-600">{{ rolePermissions.role }}</p>
            <p class="text-gray-600">{{ rolePermissions.permissions[0] }}</p>


        </template>
        <template class="flex items-center gap-5">
            <Card style="width: 16rem; overflow: hidden" v-for="movie of movies" :key="movie.id">
                <template #header>
                    <img class="w-full h-52 object-cover object-center hover:scale-110 transition duration-500"
                        :src="`/storage/images/${movie.image}`" alt="Movie header" />
                </template>
                <template #title>{{ movie.title }}</template>
                <template #subtitle>{{ movie.genre }}</template>
                <template #content>
                    <p class="m-0 text-xs">{{ movie.description }}</p>
                </template>
                <template #footer>
                    <div class="flex justify-between gap-4 mt-1">
                        <p class="text-xs text-gray-500">{{ movie.running_time }}</p>
                        <p class="text-xs text-gray-500">{{ movie.year }}</p>
                    </div>
                </template>
            </Card>
        </template>
    </AuthenticatedLayout>
</template>