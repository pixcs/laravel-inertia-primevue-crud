<script setup>
    import NavLink from './NavLink.vue';
</script>

<template>
    <ul class="flex items-center gap-x-4 m-4">
        <li>
            <NavLink href="/" class="hover:underline" :isActive=" $page.component === 'Home' ">Home</NavLink>
        </li>
        <li>
            <NavLink href="/users?name=test" class="hover:underline" :isActive=" $page.component === 'Users'">
                Users
            </NavLink>
        </li>
        <li>
            <NavLink href="/settings" class="hover:underline" :isActive=" $page.component === 'Settings'">
                Settings
            </NavLink>
        </li>
    </ul>
</template>