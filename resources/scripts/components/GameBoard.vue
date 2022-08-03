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
            <div class="flex items-center w-full px-4 h-10">
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

<script lang="ts" setup>
import { computed, ComputedRef, onBeforeUnmount, provide, onMounted } from "vue";
import axios from "axios";
import draggable from "vuedraggable";
import { Inertia } from "@inertiajs/inertia";
import { PlusIcon } from "@heroicons/vue/solid";

import { History, Focus, PaletteItem, Legacy } from "@/types";
import PeriodCard from "@/components/PeriodCard.vue";
import HistorySeed from "@/components/HistorySeed.vue";
import PeriodModal from "@/components/Modal/PeriodModal.vue";
import PrimaryButton from "@/components/UI/PrimaryButton.vue";
import GameSidebar from "@/components/GameSidebar.vue";
import GameLog from "@/components/GameLog.vue";

const props = defineProps<{
    history: History,
    palettes: Array<PaletteItem>,
    foci: Array<Focus>,
    legacies: Array<Legacy>,
}>();

const nextPosition: ComputedRef<number> = computed(() => {
    if (props.history.periods.length === 0) {
        return 1;
    }

    return props.history.periods.slice(-1)[0].position + 1;
});

const resyncBoard = (...only: string[]) =>
    Inertia.reload({ only: only });

const updateSeed = ({ name }) => props.history.name = name;

const onPeriodMoved = (e) => {
    if (!e.moved) {
        return;
    }

    const { element, newIndex } = e.moved;

    Inertia.post(
        route("periods.move", [props.history, element]),
        { position: newIndex + 1 },
        { only: ["history"] }
    );
};

onMounted(() => {
    Echo.join(channelName)
        .listen("BoardUpdated", () => resyncBoard("history"))
        .listen("FocusDefined", () => resyncBoard("foci"))
        .listen("FocusUpdated", () => resyncBoard("foci"))
        .listen("FocusDeleted", () => resyncBoard("foci"))
        .listen("ItemAddedToPalette", () => resyncBoard("palettes"))
        .listen("PaletteItemUpdated", () => resyncBoard("palettes"))
        .listen("PaletteItemDeleted", () => resyncBoard("palettes"))
        .listen("LegacyCreated", () => resyncBoard("legacies"))
        .listen("LegacyUpdated", () => resyncBoard("legacies"))
        .listen("LegacyDeleted", () => resyncBoard("legacies"))
        .listen("HistorySeedUpdated", updateSeed);
});

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
</script>
