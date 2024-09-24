<script setup>
   import Nav from "../Shared/Nav.vue";
   import { defineProps } from "vue";
   import { Link } from "@inertiajs/vue3";

   defineProps({
    time: String
   })

   let ok = true;
</script>


<template>
    <h1 class="text-2xl">Users Page</h1>
    <Nav />

    <div>
        <p class="mt-[800px]">The current time is {{ time }}.</p>
        <Link href="/users"  preserve-scroll>Refresh</Link>
    </div>
    
    {{ ok ? "Yes" : "No" }}
</template>