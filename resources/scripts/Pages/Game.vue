<template>
    <Head :title="history.name" />

    <GameBoard
        :history="history"
        :foci="foci"
        :legacies="legacies"
        :palettes="palettes"
        :echo-settings="echoGameSettings"
    />
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Game.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Head } from "@inertiajs/inertia-vue3";

import GameBoard from "@/components/GameBoard.vue";
import {
    EchoGameSettings,
    Focus,
    History,
    Legacy,
    PaletteItem
} from "@/types";

const props = defineProps<{
    history: History,
    foci: Array<Focus>,
    legacies: Array<Legacy>,
    palettes: Array<PaletteItem>,
    echoGameSettings: EchoGameSettings|null
}>();
</script>
