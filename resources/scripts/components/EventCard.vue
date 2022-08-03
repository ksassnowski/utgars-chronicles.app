<template>
    <div class="relative group">
        <GameCard :type="event.type" label="Event">
            <template #menu>
                <EventModal :event="event" :period="period">
                    <CardButton :type="event.type" />
                </EventModal>
            </template>

            <h3 class="text-center whitespace-pre-wrap text-sm">
                {{ event.name }}
            </h3>

            <template #footer>
                <EventModal :period="period" :position="event.position">
                    <CardButton
                        :type="event.type"
                        class="flex items-center -space-x-0.5"
                        title="Add event before"
                    >
                        <ArrowSmUpIcon class="w-4 h-4" />
                        <PlusIcon class="w-3 h-3" />
                    </CardButton>
                </EventModal>

                <SceneModal :event="event">
                    <button
                        class="text-sm"
                        :class="{
                            'text-indigo-700': event.type === 'light',
                            'text-indigo-300': event.type === 'dark',
                        }"
                    >
                        Add Scene
                    </button>
                </SceneModal>

                <EventModal :period="period" :position="event.position + 1">
                    <CardButton
                        :type="event.type"
                        class="flex items-center -space-x-0.5"
                        title="Add event after"
                    >
                        <PlusIcon class="w-3 h-3" />
                        <ArrowSmDownIcon class="w-4 h-4" />
                    </CardButton>
                </EventModal>
            </template>
        </GameCard>

        <draggable
            :list="event.scenes"
            @change="sceneMoved"
            handle=".handle"
            item-key="id"
        >
            <template #item="{ element }">
                <SceneCard :scene="element" :event="event" />
            </template>
        </draggable>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import draggable from "vuedraggable";
import {
    MenuIcon,
    PencilIcon,
    ArrowSmUpIcon,
    ArrowSmDownIcon,
    PlusIcon,
} from "@heroicons/vue/outline";

import LoadingButton from "./LoadingButton.vue";
import SceneCard from "./SceneCard.vue";
import SceneModal from "./Modal/SceneModal.vue";
import GameCard from "./GameCard.vue";
import EventModal from "./Modal/EventModal.vue";
import CardButton from "./CardButton.vue";

export default defineComponent({
    name: "EventCard",

    props: {
        event: Object,
        period: Object,
    },

    inject: ["history"],

    components: {
        CardButton,
        EventModal,
        GameCard,
        SceneModal,
        draggable,
        SceneCard,
        LoadingButton,
        MenuIcon,
        PencilIcon,
        ArrowSmUpIcon,
        ArrowSmDownIcon,
        PlusIcon,
    },

    methods: {
        sceneMoved(e) {
            if (!e.moved) {
                return;
            }

            this.$inertia.post(
                this.route("scenes.move", [this.history, e.moved.element]),
                { position: e.moved.newIndex + 1 },
                { only: ["history"] }
            );
        },
    },
});
</script>
