<template>
    <div class="flex flex-col flex-1">
        <header class="px-4 pt-4 pb-4 flex justify-between items-center bg-indigo-800 mb-8">
            <nav>
                <ul class="flex">
                    <li>
                        <InertiaLink :href="$route('home')" class="hover:text-white text-indigo-200">Home</InertiaLink>
                    </li>
                </ul>
            </nav>

            <div class="flex items-center">
                <Gravatar
                    :email="$page.auth.user.email"
                    :size="40"
                    class="mr-2 rounded-full border-2 border-indigo-500"
                />

                <div class="mr-4">
                    <span class="text-indigo-400">Hello, </span>
                    <span class="font-bold text-indigo-100">{{ $page.auth.user.name }}</span>
                </div>

                <form :action="$route('logout')" method="POST">
                    <input type="hidden" name="_token" :value="token">
                    <button class="text-sm text-indigo-200">Logout</button>
                </form>
            </div>
        </header>

        <slot />

        <portal-target name="modal" slim></portal-target>
    </div>
</template>

<script>
import Gravatar from 'vue-gravatar';

export default {
    name: 'Layout',

    components: {
        Gravatar,
    },

    computed: {
        token() {
            return document.head.querySelector('meta[name=csrf-token]').getAttribute('content');
        }
    },

    methods: {
        logout() {
            this.$inertia.post(this.$route('logout'));
        },
    },
};
</script>
