<template>
    <button
        class="transition duration-300 text-gray-500 hover:text-gray-600 hover:bg-black/5 p-2 rounded"
        @click="copy"
    >
        <component :is="icon" class="w-6 h-6" />
    </button>
</template>

<script lang="ts" setup>
import { ref, computed } from "vue";

import { ClipboardIcon, ClipboardCheckIcon } from "@heroicons/vue/outline";

const props = defineProps<{ contents: string }>();

const clicked = ref(false);
const icon = computed(() => clicked.value ? ClipboardCheckIcon : ClipboardIcon);

const copy = async () => {
    await navigator.clipboard.writeText(props.contents);

    clicked.value = true;

    setTimeout(() => clicked.value = false, 3000);
};
</script>
