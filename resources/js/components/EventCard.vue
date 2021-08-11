<template>
    <div class="relative game-card">
        <GameCard :type="event.type" label="Event">
            <h3 class="text-center whitespace-normal">
                {{ event.name }}
            </h3>

            <template #footer>
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
            </template>
        </GameCard>

        <draggable
            :list="event.scenes"
            @change="sceneMoved"
            handle=".handle"
            item-key="id"
        >
            <template #item="{ element }">
                <SceneCard :scene="element" />
            </template>
        </draggable>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import draggable from "vuedraggable";
import { MenuIcon } from "@heroicons/vue/solid";

import LoadingButton from "./LoadingButton.vue";
import SceneCard from "./SceneCard.vue";
import SceneModal from "./Modal/SceneModal.vue";
import GameCard from "./GameCard.vue";

export default defineComponent({
    name: "EventCard",

    props: {
        event: Object,
        period: Object,
    },

    inject: ["history"],

    components: {
        GameCard,
        SceneModal,
        draggable,
        SceneCard,
        LoadingButton,
        MenuIcon,
    },

    methods: {
        sceneMoved(e) {
            if (!e.moved) {
                return;
            }

            this.$inertia.post(
                this.$route("scenes.move", [this.history, e.moved.element]),
                { position: e.moved.newIndex + 1 },
                { only: ["history"] }
            );
        },

        remove() {
            const confirmed = confirm(
                "Really delete this event? All scenes belonging to this event will be deleted too!"
            );

            if (!confirmed) {
                return;
            }

            this.$inertia.delete(
                this.$route("events.delete", [this.history, this.event]),
                { only: ["history"] }
            );
        },
    },
});
</script>
