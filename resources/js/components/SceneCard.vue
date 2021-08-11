<template>
    <div class="mt-4 px-6">
        <GameCard :type="scene.type" label="Scene">
            <template #menu>
                <SceneModal :scene="scene" :event="event">
                    <CardButton :type="scene.type" />
                </SceneModal>

                <CardButton
                    @click="toggle"
                    :title="open ? 'Collapse Scene' : 'Expand Scene'"
                    :type="scene.type"
                >
                    <component
                        :is="open ? 'EyeOffIcon' : 'EyeIcon'"
                        class="w-4 h-4"
                    />
                </CardButton>
            </template>

            <p class="text-sm whitespace-pre-wrap" :class="{ 'pb-2': open }">
                {{ scene.question }}
            </p>

            <template v-if="open">
                <hr />

                <p v-if="scene.scene" class="text-sm py-2 whitespace-pre-wrap">
                    {{ scene.scene }}
                </p>
                <p
                    v-else
                    class="text-sm py-2 text-gray-600 italic whitespace-normal"
                >
                    This scene has no description.
                </p>

                <hr />

                <p v-if="scene.answer" class="text-sm pt-2 whitespace-pre-wrap">
                    {{ scene.answer }}
                </p>
                <p v-else class="text-sm pt-2 text-gray-600 italic">
                    This scene has not been answered yet.
                </p>
            </template>
        </GameCard>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, inject } from "vue";
import { MenuIcon, EyeIcon, EyeOffIcon } from "@heroicons/vue/solid";

import { useEmitter } from "../composables/useEmitter";
import LoadingButton from "./LoadingButton.vue";
import GameCard from "./GameCard.vue";
import SceneModal from "./Modal/SceneModal.vue";
import CardButton from "./CardButton.vue";

export default defineComponent({
    name: "SceneCard",

    components: {
        CardButton,
        SceneModal,
        GameCard,
        LoadingButton,
        MenuIcon,
        EyeIcon,
        EyeOffIcon,
    },

    props: {
        scene: Object,
        event: Object,
    },

    methods: {
        remove() {
            const confirmed = confirm(
                "Are you sure you want to delete this scene?"
            );

            if (!confirmed) {
                return;
            }

            this.$inertia.delete(
                this.$route("scenes.delete", [this.history, this.scene]),
                { only: ["history"] }
            );
        },
    },

    setup() {
        const open = ref(false);
        const toggle = () => (open.value = !open.value);
        const history = inject("history");

        const emitter = useEmitter();
        emitter.on("scenes:toggle", toggle);

        return { open, toggle, history };
    },
});
</script>
