<template>
    <GameBoard :history="$page.history" />
</template>

<script>
import GameBoard from '../components/GameBoard';
import Layout from './Layouts/Layout';

export default {
    name: 'Game',

    layout: Layout,

    components: {
        GameBoard,
    },
};
</script>
