<template>
    <div class="flex flex-col" style="flex: 1 0 auto;">
        <nav class="px-4 flex flex-wrap items-center bg-indigo-800 mb-8 justify-between">
            <InertiaLink :href="$route('home')" class="text-xl font-bold tracking-tight text-indigo-100 mr-8">
                Utgar's Chronicles
            </InertiaLink>

            <div class="block sm:hidden py-6">
                <button @click="toggle" class="px-3 py-2 border-indigo-100 text-indigo-100 border rounded hover:border-white hover:text-white">
                    <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                </button>
            </div>

            <UserNavigation :class="open ? 'block' : 'hidden'"/>
        </nav>

        <main class="flex-1">
            <FlashMessage />

            <slot />
        </main>

        <footer class="page-footer mt-20 pt-8 text-xs text-center pb-4 relative text-gray-500">
            Created in Munich by <a href="https://twitter.com/@warsh33p" target="_blank" rel="noreferrer noopener">@warsh33p</a> &mdash; &copy; {{ new Date().getFullYear() }}
        </footer>

        <portal-target name="modal" slim></portal-target>
    </div>
</template>

<script>
import UserNavigation from "../../components/UserNavigation";
import FlashMessage from "../../components/FlashMessage";

export default {
    name: 'Layout',

    components: {
        FlashMessage,
        UserNavigation,
    },

    data() {
        return {
            open: false,
        };
    },

    methods: {
        toggle() {
            this.open = !this.open;
        },
    },
};
</script>
