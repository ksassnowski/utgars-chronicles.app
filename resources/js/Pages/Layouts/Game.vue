<template>
    <div class="flex flex-col flex-1 game-container pt-4">
        <main class="flex-1 flex">
            <slot />
        </main>
    </div>
</template>

<script>
import Navigation from "../../components/Navigation.vue";

export default {
    name: 'Layout',

    components: {
        Navigation,
    },
};
</script>
