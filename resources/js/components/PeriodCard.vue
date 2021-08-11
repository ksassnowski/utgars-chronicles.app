<template>
    <div class="max-h-full flex flex-col">
        <div class="game-card">
            <article
                class="
                    p-5
                    rounded-lg
                    border border-gray-200
                    mb-4
                    shadow-sm
                    relative
                    panzoom-exclude
                    group
                    whitespace-normal
                "
                :class="{
                    'bg-gradient-to-br from-gray-600 to-gray-700 text-white':
                        period.type === 'dark',
                    'bg-gradient-to-br from-white to-gray-100 text-gray-700':
                        period.type === 'light',
                }"
            >
                <div
                    class="
                        sm:invisible sm:group-hover:visible
                        absolute
                        left-0
                        top-0
                        w-full
                        pl-3
                        pr-2
                        pt-2
                        flex
                        justifybetween
                        z-20
                    "
                >
                    <MenuIcon
                        class="handle w-5 h-5 text-gray-400 cursor-move"
                    />
                </div>

                <h3 class="font-bold tracking-wide text-center py-3 sm:py-2">
                    {{ period.name }}
                </h3>

                <p
                    class="
                        absolute
                        top-0
                        text-sm
                        font-bold
                        leading-loose
                        uppercase
                        px-1
                    "
                    :class="{
                        'text-gray-700': period.type === 'light',
                        'text-white': period.type === 'dark',
                    }"
                    style="top: -15px; right: 20px"
                >
                    Period
                </p>

                <div
                    class="
                        flex
                        justify-end
                        absolute
                        inset-x-0
                        bottom-0
                        p-2
                        sm:invisible sm:group-hover:visible
                    "
                >
                    <CreateEventModal
                        :period="period"
                        :position="nextEventPosition"
                    >
                        <button
                            class="text-sm"
                            :class="{
                                'text-indigo-700': period.type === 'light',
                                'text-indigo-300': period.type === 'dark',
                            }"
                        >
                            Add Event
                        </button>
                    </CreateEventModal>
                </div>
            </article>
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
import CreateEventModal from "./Modal/CreateEventModal.vue";
import LoadingButton from "./LoadingButton.vue";

export default defineComponent({
    name: "PeriodCard",

    props: {
        period: Object,
        historyId: Number,
    },

    components: {
        MenuIcon,
        LoadingButton,
        CreateEventModal,
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
                this.$route("events.move", [this.historyId, e.moved.element]),
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
                    this.$route("periods.delete", [
                        this.historyId,
                        this.period,
                    ]),
                    { only: ["history"] }
                );
            }
        },
    },
});
</script>
