<template>
    <GameBoard :history="$page.history" />
</template>

<script>
import GameBoard from '../components/GameBoard';

export default {
    name: 'Game',

    components: {
        GameBoard,
    },

    mounted() {
    }
};
</script>
