<template>
    <div class="max-h-full flex flex-col overflow-x-hidden overflow-y-auto space-y-4 pt-4">
        <div class="relative group">
            <GameCard :type="period.type" label="Period">
                <template #menu>
                    <PeriodModal :period="period" :history="history">
                        <CardButton :type="period.type" />
                    </PeriodModal>
                </template>

                <h3 class="font-bold tracking-wide text-center">
                    {{ period.name }}
                </h3>

                <template #footer>
                    <PeriodModal :history="history" :position="period.position">
                        <CardButton
                            :type="period.type"
                            class="flex items-center -space-x-0.5"
                            title="Add Period before"
                        >
                            <ArrowSmLeftIcon class="w-4 h-4" />
                            <PlusIcon class="w-3 h-3" />
                        </CardButton>
                    </PeriodModal>

                    <EventModal :period="period" :position="nextEventPosition">
                        <button
                            class="text-sm"
                            :class="{
                                'text-indigo-700': period.type === 'light',
                                'text-indigo-300': period.type === 'dark',
                            }"
                        >
                            Add Event
                        </button>
                    </EventModal>

                    <PeriodModal
                        :history="history"
                        :position="period.position + 1"
                    >
                        <CardButton
                            :type="period.type"
                            class="flex items-center -space-x-0.5"
                            title="Add period after"
                        >
                            <PlusIcon class="w-3 h-3" />
                            <ArrowSmRightIcon class="w-4 h-4" />
                        </CardButton>
                    </PeriodModal>
                </template>
            </GameCard>
        </div>

        <draggable
            :list="period.events"
            @change="eventMoved"
            class="space-y-4"
            style="flex: 1 1 auto"
            handle=".handle"
            item-key="id"
        >
            <template #item="{ element }">
                <EventCard :event="element" :period="period" />
            </template>
        </draggable>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import draggable from "vuedraggable";
import {
    PlusIcon,
    MenuIcon,
    ArrowSmLeftIcon,
    ArrowSmRightIcon,
} from "@heroicons/vue/outline";

import EventCard from "./EventCard.vue";
import Modal from "./Modal.vue";
import EventModal from "./Modal/EventModal.vue";
import LoadingButton from "./LoadingButton.vue";
import GameCard from "./GameCard.vue";
import PeriodModal from "./Modal/PeriodModal.vue";
import CardButton from "./CardButton.vue";

export default defineComponent({
    name: "PeriodCard",

    props: {
        period: Object,
        history: Object,
    },

    components: {
        ArrowSmLeftIcon,
        ArrowSmRightIcon,
        PlusIcon,
        CardButton,
        PeriodModal,
        GameCard,
        MenuIcon,
        LoadingButton,
        EventModal,
        Modal,
        draggable,
        EventCard,
    },

    computed: {
        nextEventPosition() {
            const last = this.period.events.slice(-1)[0];

            if (!last) {
                return 1;
            }

            return last.position + 1;
        },
    },

    methods: {
        eventMoved(e) {
            if (!e.moved) {
                return;
            }

            this.$inertia.post(
                this.route("events.move", [this.history, e.moved.element]),
                { position: e.moved.newIndex + 1 },
                { only: ["history"] }
            );
        },
    },
});
</script>
