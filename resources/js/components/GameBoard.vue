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
import { defineComponent, onBeforeUnmount } from "vue";
import draggable from 'vuedraggable';
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

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

export default defineComponent({
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

    setup(props) {
        const channelName = `history.${props.history.id}`;
        onBeforeUnmount(() => Echo.leave(channelName));

        // In order for `broadcast()->toOthers()` to work properly, we need to
        // ensure that every request we send includes the `X-Socket-ID` header
        // that Laravel uses internally to exclude the current user from the
        // broadcast.
        // Inertia.js doesn’t add this header to the request by default,
        // so we have to do it ourselves here. Otherwise, the user sending the
        // request will always resync the board as well which causes weird things
        // to happen (like the `onSuccess` callback not properly working).
        axios.interceptors.request.use((config) => {
            config.headers['X-Socket-ID'] = Echo.socketId();
            return config;
        });

        return { channelName };
    }
});
</script>
