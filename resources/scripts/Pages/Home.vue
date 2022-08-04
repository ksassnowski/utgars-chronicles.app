<template>
    <Head title="My Games" />

    <div class="mx-auto max-w-7xl px-4">
        <section class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <PageHeader>
                    Histories
                </PageHeader>

                <CreateHistoryModal>
                    <PrimaryButton>Start new history</PrimaryButton>
                </CreateHistoryModal>
            </div>

            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <li v-for="history in histories" :key="history.id" class="h-48">
                    <HistoryCard
                        class="transition duration-300 ease-in-out transform hover:rotate-1"
                        :history="history"
                        :key="history.id"
                        :url="route('history.show', history)"
                    />
                </li>
            </ul>
        </section>

        <section v-if="games.length > 0">
            <h2 class="font-bold text-xl mb-4">
                Your Games
            </h2>

            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <li v-for="game in games" :key="game.id" class="h-48">
                    <HistoryCard
                        :history="game"
                        :key="game.id"
                        :url="route('user.games.show', game)"
                    />
                </li>
            </ul>
        </section>
    </div>
</template>

<script lang="ts">
import layout from './Layouts/Layout.vue';

export default { layout }
</script>

<script lang="ts" setup>
import { Head } from "@inertiajs/inertia-vue3";

import HistoryCard from '@/components/HistoryCard.vue';
import CreateHistoryModal from "@/components/Modal/CreateHistoryModal.vue";
import PrimaryButton from "@/components/UI/PrimaryButton.vue";
import PageHeader from "@/components/UI/PageHeader.vue";

interface History {
    id: number;
    name: string;
}

const props = defineProps<{ histories: History[], games: History[] }>();
</script>
