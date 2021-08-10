<template>
    <div class="h-full flex flex-col">
        <div class="flex items-center w-full px-4 mb-4">
            <div class="flex-1">
                <Link :href="$route('home')" class="text-gray-800 font-semibold">&laquo; back</Link>
            </div>

            <HistorySeed :history="history" />

            <div class="flex-1 flex justify-end">
                <CreatePeriodModal :position="nextPosition" :history="history">
                    <PrimaryButton>Add Period</PrimaryButton>
                </CreatePeriodModal>
            </div>
        </div>

        <div class="flex-grow relative">
            <draggable
                :list="history.periods"
                @change="onPeriodMoved"
                handle=".handle"
                class="absolute inset-0 overflow-x-auto overflow-y-hidden whitespace-nowrap pb-4 px-3 space-x-2"
                item-key="id"
            >
                <template #item="{element}">
                    <div class="w-72 inline-block px-1 h-full align-top">
                        <PeriodCard
                            :period="element"
                            :history-id="history.id"
                        />
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import { Link } from "@inertiajs/inertia-vue3";

import PlayerList from "./PlayerList.vue";
import PeriodCard from "./PeriodCard.vue";
import FocusTracker from "./FocusTracker.vue";
import Palette from "./Palette.vue";
import LegacyTracker from "./LegacyTracker.vue";
import Modal from "./Modal.vue";
import GamePanel from "./GamePanel.vue";
import HistorySeed from "./HistorySeed.vue";
import CreatePeriodModal from "./Modal/CreatePeriodModal.vue";
import PrimaryButton from "./UI/PrimaryButton.vue";

export default {
    name: 'GameBoard',

    props: {
        history: {
            type: Object,
            required: true,
        },
    },

    components: {
        PrimaryButton,
        CreatePeriodModal,
        HistorySeed,
        GamePanel,
        Modal,
        LegacyTracker,
        draggable,
        PeriodCard,
        PlayerList,
        FocusTracker,
        Palette,
        Link,
    },

    data() {
        return {
            periods: this.history.periods,
        };
    },

    provide() {
        return {
            history: this.history,
        };
    },

    computed: {
        nextPosition() {
            if (this.history.periods.length === 0) {
                return 1;
            }

            return this.history.periods.slice(-1)[0].position + 1;
        },

        channelName() {
            return `history.${this.history.id}`;
        }
    },

    methods: {
        resyncBoard() {
            this.$inertia.reload({ only: ["history"] });
        },

        updateSeed({ name }) {
            this.history.name = name;
        },

        onPeriodMoved(e) {
            if (!e.moved) {
                return;
            }

            const { element, newIndex } = e.moved;

            this.$inertia.post(this.$route("periods.move", [this.history, element]), {
                position: newIndex + 1,
            });
        },
    },

    created() {
        Echo.join(this.channelName)
            .listen('BoardUpdated', this.resyncBoard)
            .listen('HistorySeedUpdated', this.updateSeed);
    },

    beforeUnmount() {
        Echo.leave(this.channelName);
    },
};
</script>
