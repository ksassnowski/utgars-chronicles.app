<template>
    <GameBoard :history="$page.props.history" />
</template>

<script>
import GameBoard from "../components/GameBoard";
import GameLayout from "./Layouts/Game";

export default {
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
};
</script>
