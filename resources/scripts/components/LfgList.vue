<template>
    <div class="flex">
        <div class="space-y-6 w-80">
            <LfgListing
                v-for="game in games"
                :key="game.id"
                :game="game"
            />
        </div>

        <div></div>
    </div>
</template>

<script lang="ts" setup>
import { History } from "@/types";
import LfgListing from "@/components/LfgListing.vue";

defineProps<{ games: Array<History> }>();
</script>
