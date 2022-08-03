<template>
    <div class="h-full game-container pt-4">
        <slot />
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
