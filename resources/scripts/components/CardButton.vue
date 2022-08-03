<template>
    <button
        class="rounded-lg p-1.5 text-white"
        :class="{
            'bg-black/20 hover:bg-black/30': type === CardType.Dark,
            'bg-black/30 hover:bg-black/60': type === CardType.Light,
        }"
    >
        <slot>
            <PencilIcon class="w-4 h-4" />
        </slot>
    </button>
</template>

<script lang="ts" setup>
import { PencilIcon } from "@heroicons/vue/outline";
import {CardType} from "@/types";

defineProps<{ type: CardType }>();
</script>
