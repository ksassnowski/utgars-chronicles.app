<template>
    <div class="container mx-auto px-4">
        <CreateHistoryModal v-if="showModal" @close="showModal = false" />

        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-xl">
                    Histories
                </h2>

                <button
                    @click="showModal = true"
                    class="px-4 py-2 bg-indigo-700 text-white rounded"
                >Start new history</button>
            </div>

            <ul class="history-cards">
                <li v-for="history in histories" :key="history.id">
                    <HistoryCard :history="history" :key="history.id" :url="$route('history.show', history)"/>
                </li>
            </ul>
        </section>

        <section v-if="games.length > 0">
            <h2 class="font-bold text-xl mb-4">
                Your Games
            </h2>

            <ul class="history-cards">
                <li v-for="game in games" :key="game.id">
                    <HistoryCard :history="game" :key="game.id" :url="$route('history.play', game)" />
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
import Layout from './Layouts/Layout';
import HistoryCard from '../components/HistoryCard';
import CreateHistoryModal from "../components/Modal/CreateHistoryModal";

export default {
    name: 'HomePage',

    layout: Layout,

    props: ['histories', 'games'],

    components: {
        CreateHistoryModal,
        HistoryCard,
    },

    data() {
        return {
            showModal: false,
        };
    },
};
</script>
