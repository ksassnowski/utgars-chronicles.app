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
                    bg-linear-to-r
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

<script lang="ts" setup>
import { inject, onMounted } from "vue";
import {
    SparklesIcon,
    ThumbDownIcon,
    ThumbUpIcon,
    UserAddIcon,
    UserRemoveIcon,
    XIcon,
} from "@heroicons/vue/outline";

import {PaletteItem, PaletteType, Player} from "@/types";
import {useGameLog} from "@/composables/useGameLog";
import { ChannelKey } from "@/symbols";

const { messages, addMessage } = useGameLog();
const channelName = inject(ChannelKey);

const onPaletteItemAdded = (item: PaletteItem) =>
    addMessage({
        title: "Added to Palette",
        message: item.name,
        icon: item.type === PaletteType.Yes ? ThumbUpIcon : ThumbDownIcon,
    });

const onPaletteItemRemoved = (item: PaletteItem) =>
    addMessage({
        title: "Removed from Palette",
        message: item.name,
        icon: item.type === "yes" ? ThumbUpIcon : ThumbDownIcon,
    });

const onLegacyCreated = ({ name }) =>
    addMessage({
        title: "New Legacy",
        message: name,
        icon: SparklesIcon,
    });

onMounted(() => {
    Echo.join(channelName)
        .joining((player: Player) =>
            addMessage({
                title: "Player joined",
                message: player.name,
                icon: UserAddIcon,
            })
        )
        .leaving((player) =>
            addMessage({
                title: "Player left",
                message: player.name,
                icon: UserRemoveIcon,
            })
        )
        .listen("ItemAddedToPalette", onPaletteItemAdded)
        .listen("PaletteItemDeleted", onPaletteItemRemoved)
        .listen("LegacyCreated", onLegacyCreated);
});
</script>
