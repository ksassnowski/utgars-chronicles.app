<template>
    <div class="flex flex-col flex-1 game-container">
        <Navigation />

        <main class="flex-1 flex">
            <slot />
        </main>

        <portal-target name="modal" slim></portal-target>
    </div>
</template>

<script>
import Navigation from "../../components/Navigation";

export default {
    name: 'Layout',

    components: {
        Navigation,
    },
};
</script>
