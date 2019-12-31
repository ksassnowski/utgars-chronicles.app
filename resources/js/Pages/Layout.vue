<template>
    <div class="flex flex-col flex-1">
        <nav class="px-4 pt-4 pb-4 flex justify-end items-center bg-indigo-800">
            <Gravatar
                :email="$page.auth.user.email"
                :size="40"
                class="mr-2 rounded-full border-2 border-indigo-500"
            />

            <div class="mr-4">
                <span class="text-indigo-400">Hello, </span>
                <span class="font-bold text-indigo-100">{{ $page.auth.user.name }}</span>
            </div>

            <form @submit.prevent="logout">
                <button class="text-sm text-indigo-200">Logout</button>
            </form>
        </nav>

        <slot />
    </div>
</template>

<script>
import Gravatar from 'vue-gravatar';

export default {
    name: 'Layout',

    components: {
        Gravatar,
    },

    methods: {
        logout() {
            axios.post(this.$route('logout'));
        },
    },
};
</script>
