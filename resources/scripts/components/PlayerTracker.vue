<template>
    <SettingsPopover
        title="Players"
        button-text="Players"
        :pinnable-type="PinnedItemType.Players"
    >
        <template #icon>
            <UsersIcon class="w-4 h-4" />
        </template>

        <template #description>
            See which players are currently in the game.
        </template>

        <ul class="space-y-3">
            <li
                v-for="player in players"
                class="py-2 px-4 shadow shadow-indigo-100 ring-1 ring-indigo-50 rounded text-sm text-gray-800 font-medium"
            >
                {{ player.name }}
            </li>
        </ul>
    </SettingsPopover>
</template>

<script lang="ts" setup>
import { UsersIcon } from "@heroicons/vue/outline";

import { PinnedItemType } from "@/types";
import SettingsPopover from "@/components/SettingsPopover.vue";
import { usePlayerList } from "@/composables/usePlayerList";

const players = usePlayerList();
</script>
