<template>
    <div class="container mx-auto pt-8 px-4">
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-xl">
                    Histories
                </h2>

                <InertiaLink
                    :href="$route('history.create')"
                    class="px-4 py-2 bg-indigo-700 text-white rounded"
                >Start new history</InertiaLink>
            </div>

            <ul class="history-cards">
                <li v-for="history in $page.histories" :key="history.id">
                    <HistoryCard :history="history" :key="history.id" />
                </li>
            </ul>
        </section>

        <section>
            <h2 class="font-bold text-xl mb-4">
                Your Games
            </h2>

            <ul class="history-cards">
                <li v-for="game in $page.games" :key="game.id">
                    <HistoryCard :history="game" :key="game.id" />
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
import Layout from "./Layouts/Layout";
import HistoryCard from '../components/HistoryCard';

export default {
    name: 'HomePage',

    layout: Layout,

    components: {
        HistoryCard,
    },
};
</script>
