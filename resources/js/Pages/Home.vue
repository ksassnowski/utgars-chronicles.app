<template>
    <div class="container mx-auto px-4 pt-8">
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-xl">
                    Histories
                </h2>

                <CreateHistoryModal>
                    <PrimaryButton>Start new history</PrimaryButton>
                </CreateHistoryModal>
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
                    <HistoryCard :history="game" :key="game.id" :url="$route('user.games.show', game)" />
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
import Layout from './Layouts/Layout.vue';
import HistoryCard from '../components/HistoryCard.vue';
import CreateHistoryModal from "../components/Modal/CreateHistoryModal.vue";
import PrimaryButton from "../components/UI/PrimaryButton.vue";

export default {
    name: 'HomePage',

    metaInfo() {
        return {
            title: 'Utgar\'s Chronicles – Play Microscope Online'
        };
    },

    layout: Layout,

    props: ['histories', 'games'],

    components: {
        PrimaryButton,
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
