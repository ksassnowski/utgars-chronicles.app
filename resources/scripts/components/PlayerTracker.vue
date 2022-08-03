<template>
    <SettingsPopover title="Players" button-text="Players">
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
import { ref, onMounted, inject } from "vue";
import { UsersIcon } from "@heroicons/vue/outline";

import { Player } from "@/types";
import { ChannelKey } from "@/symbols";

import SettingsPopover from "@/components/SettingsPopover.vue";

const players = ref<Array<Player>>([]);
const channel = inject(ChannelKey);

onMounted(() => {
    Echo.join(channel)
        .here((users: Array<Player>) => players.value = users)
        .joining((user: Player) => players.value.push(user))
        .leaving((user: Player) =>
            players.value = players.value.filter(p => p.id !== user.id)
        );
})
</script>
