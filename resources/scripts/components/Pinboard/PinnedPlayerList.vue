<template>
    <Pinnable title="Player List">
        <ul class="space-y-3">
            <li
                v-for="player in players"
                class="py-2 px-4 shadow shadow-indigo-100 ring-1 ring-indigo-50 rounded text-sm text-gray-800 font-medium"
            >
                {{ player.name }}
            </li>
        </ul>
    </Pinnable>
</template>

<script lang="ts" setup>
import { usePlayerList } from "@/composables/usePlayerList";
import Pinnable from "@/components/Pinboard/Pinnable.vue";

const players = usePlayerList();
</script>
