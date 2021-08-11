<template>
    <div class="mt-4 px-6">
        <div
            class="
                relative
                pt-8
                px-4
                pb-6
                relative
                shadow-sm
                rounded-lg
                border
                bg-white
                border-gray-200
                text-sm
                w-full
                min-h-32
                group
            "
            :class="{
                'bg-gradient-to-br from-gray-600 to-gray-700 text-white':
                    scene.type === 'dark',
                'bg-gradient-to-br from-white to-gray-100 text-gray-700':
                    scene.type === 'light',
            }"
        >
            <div>
                <div
                    class="
                        invisible
                        group-hover:visible
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
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="
                            handle
                            w-4
                            h-4
                            fill-current
                            text-gray-400
                            cursor-move
                        "
                        style="margin-top: 2px"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"
                        />
                    </svg>

                    <div class="flex items-center">
                        <button
                            @click="open = !open"
                            class="mr-2"
                            :title="open ? 'Collapse Scene' : 'Expand Scene'"
                            style="margin-top: -2px"
                        >
                            <Icon
                                class="w-4 h-4 fill-current text-gray-600"
                                :name="open ? 'view-hide' : 'view-show'"
                            />
                        </button>
                    </div>
                </div>

                <p
                    class="text-sm whitespace-pre-wrap"
                    :class="{ 'pb-2': open }"
                >
                    {{ scene.question }}
                </p>

                <template v-if="open">
                    <hr />

                    <p
                        v-if="scene.scene"
                        class="text-sm py-2 whitespace-pre-wrap"
                    >
                        {{ scene.scene }}
                    </p>
                    <p
                        v-else
                        class="
                            text-sm
                            py-2
                            text-gray-600
                            italic
                            whitespace-normal
                        "
                    >
                        This scene has no description.
                    </p>

                    <hr />

                    <p
                        v-if="scene.answer"
                        class="text-sm pt-2 whitespace-pre-wrap"
                    >
                        {{ scene.answer }}
                    </p>
                    <p v-else class="text-sm pt-2 text-gray-600 italic">
                        This scene has not been answered yet.
                    </p>
                </template>

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
                        'text-white': scene.tone === 'dark',
                        'text-gray-700': scene.type === 'light',
                    }"
                    style="top: -15px; right: 20px"
                >
                    Scene
                </p>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

import Icon from "./Icon.vue";
import LoadingButton from "./LoadingButton.vue";

export default defineComponent({
    name: "SceneCard",

    components: {
        LoadingButton,
        Icon,
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
