<template>
    <div
        class="
            fixed
            z-30
            top-4
            sm:top-auto sm:bottom-6
            right-4
            sm:right-32
            space-y-2
        "
    >
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
                    bg-gradient-to-r
                    from-indigo-500
                    to-indigo-700
                    rounded-md
                    shadow
                    pl-5
                    pr-8
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
                    :is="message.icon"
                    class="w-4 h-4 mr-2 mt-1 text-white"
                />

                <div class="flex-1">
                    <p
                        class="
                            text-xs text-indigo-200
                            tracking-tight
                            font-normal
                        "
                    >
                        {{ message.title }}
                    </p>
                    <p class="text-white">{{ message.message }}</p>
                </div>

                <button
                    @click="message.close"
                    class="absolute top-0 right-0 p-1"
                >
                    <XIcon class="w-4 h-4 text-indigo-300" />
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
                icon: item.type === "yes" ? "ThumbUpIcon" : "ThumbDownIcon",
            });
        },

        onPaletteItemRemoved(item: PaletteItem) {
            this.addMessage({
                title: "Removed from Palette",
                message: item.name,
                icon: item.type === "yes" ? "ThumbUpIcon" : "ThumbDownIcon",
            });
        },

        onLegacyCreated({ name }) {
            this.addMessage({
                title: "New Legacy",
                message: name,
                icon: "SparklesIcon",
            });
        },
    },

    mounted() {
        Echo.join(this.channelName)
            .joining((player: Player) =>
                this.addMessage({
                    title: "Player joined",
                    message: player.name,
                    icon: "UserAddIcon",
                })
            )
            .leaving((player) =>
                this.addMessage({
                    title: "Player left",
                    message: player.name,
                    icon: "UserRemoveIcon",
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
