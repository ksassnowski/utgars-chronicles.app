<template>
    <div class="mt-4 px-6">
        <GameCard :type="scene.type" label="Scene">
            <template #menu-left>
                <button
                    @click="open = !open"
                    :title="open ? 'Collapse Scene' : 'Expand Scene'"
                    style="margin-top: -2px"
                >
                    <component
                        :is="open ? 'EyeOffIcon' : 'EyeIcon'"
                        class="w-5 h-5"
                        :class="
                            scene.type === 'dark'
                                ? 'text-gray-300'
                                : 'text-gray-500'
                        "
                    />
                </button>
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
import { defineComponent } from "vue";
import { MenuIcon, EyeIcon, EyeOffIcon } from "@heroicons/vue/solid";

import LoadingButton from "./LoadingButton.vue";
import GameCard from "./GameCard.vue";

export default defineComponent({
    name: "SceneCard",

    components: {
        GameCard,
        LoadingButton,
        MenuIcon,
        EyeIcon,
        EyeOffIcon,
    },

    props: ["scene"],

    inject: ["history"],

    data() {
        return {
            open: true,
        };
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
});
</script>
