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
                    pr-4
                    sm:pl-2 sm:pr-2 sm:pt-12
                    space-x-2
                    sm:space-x-0 sm:space-y-3
                    flex
                    items-center
                    sm:w-full sm:flex-col
                    flex-grow
                    h-full
                    sm:h-auto
                "
            >
                <PeriodModal
                    class="h-full sm:hidden"
                    :history="history"
                    :position="nextPosition"
                >
                    <button
                        class="
                            uppercase
                            text-gray-100
                            hover:text-white
                            text-xs
                            font-medium
                            bg-black bg-opacity-20
                            h-full
                            px-3
                            inline-flex
                            items-center
                        "
                    >
                        <PlusIcon class="h-4 w-4 text-indigo-100 mr-1" />
                        Period
                    </button>
                </PeriodModal>

                <FocusTracker :foci="foci" :history="history" />
                <Palette :history="history" :palette="palette" />
                <LegacyTracker :history="history" :legacies="legacies" />
            </div>

            <div class="sm:w-full">
                <button
                    v-if="$page.props.environment === 'local'"
                    @click="toggleScenes"
                    class="hidden sm:block text-white text-sm"
                >
                    Toggle Scenes
                </button>

                <Link
                    :href="route('home')"
                    class="
                        flex
                        items-center
                        sm:w-full
                        bg-indigo-800
                        sm:bg-indigo-900
                        text-indigo-100
                        font-medium
                        text-xs
                        px-2.5
                        sm:px-4
                        py-4
                        sm:mt-2
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
import { PlusIcon, ChevronDoubleLeftIcon } from "@heroicons/vue/outline";

import { useEmitter } from "../composables/useEmitter";
import LegacyTracker from "./LegacyTracker.vue";
import Palette from "./Palette.vue";
import FocusTracker from "./FocusTracker.vue";
import PeriodModal from "./Modal/PeriodModal.vue";

export default defineComponent({
    name: "GameSidebar",

    props: {
        history: Object,
        palette: Array,
        legacies: Array,
        foci: Array,
        nextPosition: Number,
    },

    components: {
        PeriodModal,
        LegacyTracker,
        Palette,
        FocusTracker,
        Link,
        ChevronDoubleLeftIcon,
        PlusIcon,
    },

    setup() {
        const emitter = useEmitter();
        const toggleScenes = () => emitter.trigger("scenes:toggle");

        return { toggleScenes };
    },
});
</script>
