<template>
    <div class="flex flex-col" style="flex: 1 0 auto;">
        <Navigation />

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
import FlashMessage from "../../components/FlashMessage";
import Navigation from "../../components/Navigation";

export default {
    name: 'Layout',

    components: {
        Navigation,
        FlashMessage,
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
