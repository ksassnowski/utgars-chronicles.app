<template>
    <div class="fixed z-30 bottom-6 right-32 space-y-2">
        <transition-group
            enter-active-class="transition duration-300 transform ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-300 transform ease-in absolute"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
            move-class="transition-all duration-300 ease-out"
        >
            <div
                v-for="message in messages"
                :key="message.id"
                class="
                    relative
                    bg-white
                    rounded-md
                    shadow-sm
                    border border-gray-200
                    pl-3
                    pr-7
                    py-3
                    text-sm text-gray-700
                    flex
                    items-start
                    font-medium
                    leading-tight
                "
            >
                <component
                    v-if="message.icon"
                    :is="message.icon.name"
                    class="w-4 h-4 mr-2 mt-1"
                    :class="message.icon.color"
                />

                <div class="flex-1">
                    <p class="text-xs text-gray-400 tracking-tight font-normal">
                        {{ message.title }}
                    </p>
                    <p>{{ message.message }}</p>
                </div>

                <button
                    @click="message.close"
                    class="absolute top-0 right-0 p-1"
                >
                    <XIcon class="w-4 h-4 text-gray-500" />
                </button>
            </div>
        </transition-group>
    </div>
</template>

<script lang="ts">
import { defineComponent, inject } from "vue";
import {
    XIcon,
    UserAddIcon,
    UserRemoveIcon,
    ThumbUpIcon,
    ThumbDownIcon,
    ExclamationCircleIcon,
    SparklesIcon,
} from "@heroicons/vue/outline";

import { useGameLog } from "../composables/useGameLog";

type Player = { id: string; name: string };
type PaletteItem = { name: string; type: "yes" | "no" };

export default defineComponent({
    name: "GameLog",

    components: {
        XIcon,
        UserAddIcon,
        UserRemoveIcon,
        ThumbUpIcon,
        ThumbDownIcon,
        ExclamationCircleIcon,
        SparklesIcon,
    },

    methods: {
        onPaletteItemAdded(item: PaletteItem) {
            this.addMessage({
                title: "Added to Palette",
                message: item.name,
                icon: {
                    name: item.type === "yes" ? "ThumbUpIcon" : "ThumbDownIcon",
                    color:
                        item.type === "yes" ? "text-green-500" : "text-red-500",
                },
            });
        },

        onPaletteItemRemoved(item: PaletteItem) {
            this.addMessage({
                title: "Removed from Palette",
                message: item.name,
                icon: {
                    name: item.type === "yes" ? "ThumbUpIcon" : "ThumbDownIcon",
                    color:
                        item.type === "yes" ? "text-green-500" : "text-red-500",
                },
            });
        },

        onLegacyCreated({ name }) {
            this.addMessage({
                title: "New Legacy",
                message: name,
                icon: {
                    name: "SparklesIcon",
                    color: "text-yellow-500",
                },
            });
        },
    },

    mounted() {
        Echo.join(this.channelName)
            .joining((player: Player) =>
                this.addMessage({
                    title: "Player joined",
                    message: player.name,
                    icon: {
                        name: "UserAddIcon",
                        color: "text-green-500",
                    },
                })
            )
            .leaving((player) =>
                this.addMessage({
                    title: "Player left",
                    message: player.name,
                    icon: {
                        name: "UserRemoveIcon",
                        color: "text-red-500",
                    },
                })
            )
            .listen("ItemAddedToPalette", this.onPaletteItemAdded)
            .listen("PaletteItemDeleted", this.onPaletteItemRemoved)
            .listen("LegacyCreated", this.onLegacyCreated);
    },

    setup() {
        const channelName = inject("channelName");
        const { messages, addMessage } = useGameLog();

        return { messages, addMessage, channelName };
    },
});
</script>
