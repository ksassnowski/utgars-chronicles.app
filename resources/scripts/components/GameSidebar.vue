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
            sm:bg-linear-to-b
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
                    sm:pl-2 sm:pr-2 sm:pt-6
                    sm:space-y-3
                    flex
                    items-center
                    justify-between
                    sm:justify-start
                    sm:w-full sm:flex-col
                    grow
                    h-full
                    sm:h-auto
                "
            >
                <Link :href="route('home')" class="mb-4 hidden sm:block">
                    <img :src="`/images/logo_without_text.svg`" class="h-16" alt="Utgar's Chronicles Logo" />
                </Link>

                <PeriodModal
                    class="h-full sm:hidden"
                    :history="history"
                    :position="nextPosition"
                >
                    <button
                        class="
                            sm:uppercase
                            text-gray-100
                            hover:text-white
                            text-[0.6rem]
                            font-medium
                            bg-black/20
                            h-full
                            px-3
                            inline-flex
                            flex-col
                            items-center
                            justify-center
                            space-y-0.5
                        "
                    >
                        <PlusIcon class="h-4 w-4 text-indigo-100" />
                        <span>Add Period</span>
                    </button>
                </PeriodModal>

                <FocusTracker :foci="foci" :history="history" />
                <Palette :history="history" :palette="palette" />
                <LegacyTracker :history="history" :legacies="legacies" />
                <PlayerTracker />
                <FactionTracker
                    v-if="history.game_mode === MicroscopeGameMode.Echo"
                    :history="history"
                />
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
                        hidden
                        sm:flex
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

<script lang="ts" setup>
import { Link } from "@inertiajs/vue3";
import { PlusIcon, ChevronDoubleLeftIcon } from "@heroicons/vue/outline";

import { Focus, History, Legacy, PaletteItem, MicroscopeGameMode } from "@/types";
import { useEmitter } from "@/composables/useEmitter";

import LegacyTracker from "@/components/LegacyTracker.vue";
import Palette from "@/components/Palette.vue";
import FocusTracker from "@/components/FocusTracker.vue";
import PeriodModal from "@/components/Modal/PeriodModal.vue";
import PlayerTracker from "@/components/PlayerTracker.vue";
import FactionTracker from "@/components/FactionTracker.vue";

const props = defineProps<{
    history: History,
    palette: Array<PaletteItem>,
    legacies: Array<Legacy>,
    foci: Array<Focus>,
    nextPosition: number,
}>();

const emitter = useEmitter();
const toggleScenes = () => emitter.trigger("scenes:toggle");
</script>
