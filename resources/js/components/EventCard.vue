<template>
    <div class="relative game-card">
        <article
            class="
                relative
                p-5
                relative
                shadow-sm
                rounded-lg
                border border-gray-200
                text-sm
                w-full
                min-h-32
                group
            "
            :class="{
                'bg-gradient-to-br from-gray-600 to-gray-700 text-white':
                    event.type === 'dark',
                'bg-gradient-to-br from-white to-gray-100 text-gray-700':
                    event.type === 'light',
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
                    justify-between
                    z-20
                "
            >
                <MenuIcon class="handle w-5 h-5 text-gray-400 cursor-move" />
            </div>

            <h4 class="text-center whitespace-normal py-3 sm:py-2">
                {{ event.name }}
            </h4>

            <div
                class="
                    absolute
                    sm:invisible sm:group-hover:visible
                    flex
                    justify-end
                    items-center
                    inset-x-0
                    bottom-0
                    px-2
                    pb-2
                "
            >
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
            </div>

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
                    'text-white': event.type === 'dark',
                    'text-gray-700': event.type === 'light',
                }"
                style="top: -15px; right: 20px"
            >
                Event
            </p>
        </article>

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

export default defineComponent({
    name: "EventCard",

    props: {
        event: Object,
        period: Object,
    },

    inject: ["history"],

    components: {
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
