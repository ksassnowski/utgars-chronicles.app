<template>
    <component
        :is="as"
        class="bg-indigo-700 text-white rounded-md text-sm font-medium px-4 py-2 transition duration-300 hover:bg-indigo-600"
    >
        <slot />
    </component>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "PrimaryButton",

    props: {
        as: {
            default: "button",
        },
    },
});
</script>
