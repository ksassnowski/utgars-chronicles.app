<template>
    <div
        class="
            fixed
            inset-x-0
            bottom-0
            bg-indigo-700
            sm:left-auto
            sm:right-0
            sm:inset-y-0
            sm:bg-gradient-to-b
            sm:from-indigo-600
            sm:to-indigo-800
            shadow-lg
            h-12
            sm:h-auto sm:w-24
            space-y-4
            z-20
        "
    >
        <div class="flex sm:flex-col h-full items-center justify-between">
            <div
                class="
                    px-4
                    sm:px-2
                    py-2
                    sm:pt-12
                    space-x-2
                    sm:space-x-0 sm:space-y-3
                    flex
                    sm:w-full sm:flex-col
                "
            >
                <FocusTracker :foci="foci" :history="history" />
                <Palette :history="history" :palette="palette" />
                <LegacyTracker :history="history" :legacies="legacies" />
            </div>

            <div>
                <!--button @click="toggleScenes" class="text-white text-sm">
                    Toggle Scenes
                </button-->

                <Link
                    :href="$route('home')"
                    class="
                        flex
                        items-center
                        sm:w-full
                        bg-indigo-800
                        sm:bg-indigo-900
                        text-indigo-100
                        font-medium
                        text-xs
                        px-4
                        py-4
                        mt-2
                        justify-center
                        hover:text-white hover:bg-black hover:bg-opacity-30
                    "
                >
                    <ChevronDoubleLeftIcon class="w-3 h-3 mr-1" />
                    back
                </Link>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ChevronDoubleLeftIcon } from "@heroicons/vue/outline";

import { useEmitter } from "../composables/useEmitter";
import LegacyTracker from "./LegacyTracker.vue";
import Palette from "./Palette.vue";
import FocusTracker from "./FocusTracker.vue";

export default defineComponent({
    name: "GameSidebar",

    props: {
        history: Object,
        palette: Array,
        legacies: Array,
        foci: Array,
    },

    components: {
        LegacyTracker,
        Palette,
        FocusTracker,
        Link,
        ChevronDoubleLeftIcon,
    },

    setup() {
        const emitter = useEmitter();
        const toggleScenes = () => emitter.trigger("scenes:toggle");

        return { toggleScenes };
    },
});
</script>
