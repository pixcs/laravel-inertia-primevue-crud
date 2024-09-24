<script setup>
    import { Link } from '@inertiajs/vue3';
    import { defineProps } from 'vue';

    defineProps({
      isActive: Boolean
    })
</script>


<template>
  <Link href="/" class="hover:underline" :class="{ 'font bold underline' : isActive }">
    <slot />
  </Link>
</template>