<template>
    <div class="bg-gray-800 mb-8">
        <nav class="mx-auto w-full max-w-7xl flex flex-wrap items-center justify-between px-4">
            <Link
                :href="route('home')"
                class="text-xl font-bold tracking-tight text-white mr-8"
            >
                Utgar’s Chronicles
            </Link>

            <div class="block md:hidden py-6">
                <button
                    @click="toggle"
                    class="px-3 py-2 border-indigo-100 text-indigo-100 border rounded hover:border-white hover:text-white"
                >
                    <svg
                        class="fill-current h-3 w-3"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>

            <UserNavigation :class="open ? 'block' : 'hidden'" />
        </nav>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import UserNavigation from "./UserNavigation.vue";

export default {
    name: "Navigation",

    components: {
        Link,
        UserNavigation,
    },

    data() {
        return {
            open: false
        };
    },

    methods: {
        toggle() {
            this.open = !this.open;
        }
    }
};
</script>
