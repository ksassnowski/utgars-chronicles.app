<template>
    <Head :title="page.props.history.name" />

    <GameBoard
        :history="page.props.history"
        :foci="page.props.foci"
        :legacies="page.props.legacies"
        :palettes="page.props.palettes"
    />
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Game.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { usePage, Head } from "@inertiajs/vue3";

import GameBoard from "@/components/GameBoard.vue";

const page = usePage();
</script>
