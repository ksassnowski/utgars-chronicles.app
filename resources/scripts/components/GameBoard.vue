<template>
    <div class="h-full flex relative">
        <GameLog />

        <GameSidebar
            :history="history"
            :next-position="nextPosition"
            :legacies="legacies"
            :foci="foci"
            :palette="palettes"
        />

        <div class="pb-12 sm:pb-0 sm:pr-24 flex flex-col flex-grow">
            <div class="flex items-center w-full px-4 mb-4 h-10">
                <div class="hidden sm:block flex-1">
                    <PeriodModal :position="nextPosition" :history="history">
                        <PrimaryButton class="flex items-center">
                            <PlusIcon class="w-4 h-4 mr-1" />
                            Add Period
                        </PrimaryButton>
                    </PeriodModal>
                </div>

                <HistorySeed :history="history" />

                <div class="flex-1 hidden sm:flex justify-end"></div>
            </div>

            <div class="flex-grow relative z-0">
                <draggable
                    :list="history.periods"
                    @change="onPeriodMoved"
                    handle=".handle"
                    class="
                        absolute
                        inset-0
                        overflow-x-auto overflow-y-hidden
                        whitespace-nowrap
                        pl-2
                        pb-3
                    "
                    item-key="id"
                >
                    <template #item="{ element }">
                        <div class="w-72 inline-block px-1.5 h-full align-top">
                            <PeriodCard :period="element" :history="history" />
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, onBeforeUnmount, provide } from "vue";
import axios from "axios";
import draggable from "vuedraggable";
import { Link } from "@inertiajs/inertia-vue3";
import { PlusIcon } from "@heroicons/vue/solid";

import PeriodCard from "./PeriodCard.vue";
import Modal from "./Modal.vue";
import HistorySeed from "./HistorySeed.vue";
import PeriodModal from "./Modal/PeriodModal.vue";
import PrimaryButton from "./UI/PrimaryButton.vue";
import GameSidebar from "./GameSidebar.vue";
import GameLog from "./GameLog.vue";

export default defineComponent({
    name: "GameBoard",

    props: {
        history: {
            type: Object,
            required: true,
        },
        palettes: {
            type: Array,
            default: () => [],
        },
        foci: {
            type: Array,
            default: () => [],
        },
        legacies: {
            type: Array,
            default: () => [],
        },
    },

    components: {
        GameLog,
        GameSidebar,
        PrimaryButton,
        PeriodModal,
        HistorySeed,
        Modal,
        draggable,
        PeriodCard,
        Link,
        PlusIcon,
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
        resyncBoard(...only: string[]) {
            this.$inertia.reload({ only: only });
        },

        updateSeed({ name }) {
            this.history.name = name;
        },

        onPeriodMoved(e) {
            if (!e.moved) {
                return;
            }

            const { element, newIndex } = e.moved;

            this.$inertia.post(
                this.route("periods.move", [this.history, element]),
                { position: newIndex + 1 },
                { only: ["history"] }
            );
        },
    },

    created() {
        Echo.join(this.channelName)
            .listen("BoardUpdated", () => this.resyncBoard("history"))
            .listen("FocusDefined", () => this.resyncBoard("foci"))
            .listen("FocusUpdated", () => this.resyncBoard("foci"))
            .listen("FocusDeleted", () => this.resyncBoard("foci"))
            .listen("ItemAddedToPalette", () => this.resyncBoard("palettes"))
            .listen("PaletteItemUpdated", () => this.resyncBoard("palettes"))
            .listen("PaletteItemDeleted", () => this.resyncBoard("palettes"))
            .listen("LegacyCreated", () => this.resyncBoard("legacies"))
            .listen("LegacyUpdated", () => this.resyncBoard("legacies"))
            .listen("LegacyDeleted", () => this.resyncBoard("legacies"))
            .listen("HistorySeedUpdated", this.updateSeed);
    },

    setup(props) {
        const channelName = `history.${props.history.id}`;
        onBeforeUnmount(() => Echo.leave(channelName));

        // In order for `broadcast()->toOthers()` to work properly, we need to
        // ensure that every request we send includes the `X-Socket-ID` header
        // that Laravel uses internally to exclude the current user from the
        // broadcast.
        // Inertia.scripts doesn’t add this header to the request by default,
        // so we have to do it ourselves here. Otherwise, the user sending the
        // request will always resync the board as well which causes weird things
        // to happen (like the `onSuccess` callback not properly working).
        axios.interceptors.request.use((config) => {
            config.headers["X-Socket-ID"] = Echo.socketId();
            return config;
        });

        provide("history", props.history);
        provide("channelName", channelName);

        return { channelName };
    },
});
</script>
