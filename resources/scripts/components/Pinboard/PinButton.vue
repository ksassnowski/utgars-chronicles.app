<template>
    <button
        type="button"
        @click="onClick"
        title="Pin item"
        class="p-2 rounded-lg text-white transition duration-150 hover:bg-black/20"
    >
        <StarIconFilled v-if="itemAlreadyPinned" class="w-4 h-4" />
        <StarIcon v-else class="w-4 h-4" />
    </button>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import { StarIcon } from "@heroicons/vue/outline";
import { StarIcon as StarIconFilled } from "@heroicons/vue/solid";

import { PinnedItemType } from "@/types";
import { usePinboard } from "@/composables/usePinboard";

const props = defineProps<{ type: PinnedItemType }>();
const { unpinItem, pinItem, itemPinned } = usePinboard();

const itemAlreadyPinned = computed(() => itemPinned(props.type));
const onClick = () => {
    if (itemAlreadyPinned.value) {
        unpinItem(props.type);
    } else {
        pinItem(props.type);
    }
}
</script>
