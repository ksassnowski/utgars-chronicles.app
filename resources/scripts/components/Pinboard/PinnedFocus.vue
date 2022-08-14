<template>
    <Pinnable title="Current Focus">
        <div
            v-if="focus"
            class="shadow-sm rounded-lg text-center font-medium z-10 relative group bg-indigo-700 text-white p-6"
        >
            {{ focus.name }}
        </div>
        <div v-else class="text-gray-500 text-sm">No current focus</div>
    </Pinnable>
</template>

<script lang="ts" setup>
import { computed, inject } from "vue";

import { FociKey } from "@/symbols";
import Pinnable from "@/components/Pinboard/Pinnable.vue";

const foci = inject(FociKey);
const focus = computed(() => foci.value.length > 0 ? foci.value[0] : null);
</script>
