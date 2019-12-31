<template>
    <div>
        <nav class="px-4 py-4 bg-indigo-800 flex justify-end items-center">
            <InertiaLink :href="$route('login')" class="text-indigo-100 px-4">Login</InertiaLink>
            <InertiaLink :href="$route('register')" class="px-4 py-2 border-2 rounded-sm border-white text-white">Register</InertiaLink>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'Index',
}
</script>
