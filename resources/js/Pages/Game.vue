<template>
    <GameBoard
        :history="$page.props.history"
        :foci="$page.props.foci"
        :legacies="$page.props.legacies"
        :palettes="$page.props.palettes"
    />
</template>

<script lang="ts">
import { defineComponent } from "vue";

import GameBoard from "../components/GameBoard.vue";
import GameLayout from "./Layouts/Game.vue";

export default defineComponent({
    name: "Game",

    metaInfo() {
        return {
            title: `${this.$page.props.history.name} – Utgar\'s Chronicles`
        };
    },

    layout: GameLayout,

    components: {
        GameBoard
    }
});
</script>
