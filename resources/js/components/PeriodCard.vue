<template>
    <div class="max-h-full flex flex-col">
        <div class="game-card">
            <GameCard :type="period.type" label="Period">
                <h3 class="font-bold tracking-wide text-center">
                    {{ period.name }}
                </h3>

                <template #footer>
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
                </template>
            </GameCard>
        </div>

        <draggable
            :list="period.events"
            @change="eventMoved"
            class="overflow-x-hidden overflow-y-auto space-y-4"
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
import { MenuIcon } from "@heroicons/vue/solid";

import EventCard from "./EventCard.vue";
import Modal from "./Modal.vue";
import EventModal from "./Modal/EventModal.vue";
import LoadingButton from "./LoadingButton.vue";
import GameCard from "./GameCard.vue";
import PeriodModal from "./Modal/PeriodModal.vue";

export default defineComponent({
    name: "PeriodCard",

    props: {
        period: Object,
        history: Object,
    },

    components: {
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
                this.$route("events.move", [this.history, e.moved.element]),
                { position: e.moved.newIndex + 1 },
                { only: ["history"] }
            );
        },

        remove() {
            const confirmed = confirm(
                "Really delete this period? This will delete all events and scenes as well!"
            );

            if (confirmed) {
                this.$inertia.delete(
                    this.$route("periods.delete", [this.history, this.period]),
                    { only: ["history"] }
                );
            }
        },
    },
});
</script>
