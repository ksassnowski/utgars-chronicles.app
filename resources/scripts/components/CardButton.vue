<template>
    <button
        class="rounded-lg p-1.5 text-white bg-black"
        :class="{
            'bg-opacity-20 hover:bg-opacity-30': type === 'dark',
            'bg-opacity-30 hover:bg-opacity-60': type === 'light',
        }"
    >
        <slot>
            <PencilIcon class="w-4 h-4" />
        </slot>
    </button>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { PencilIcon } from "@heroicons/vue/outline";

export default defineComponent({
    name: "CardButton",

    props: {
        type: String,
    },

    components: {
        PencilIcon,
    },
});
</script>
