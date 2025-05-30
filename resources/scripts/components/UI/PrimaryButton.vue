<template>
    <Link
        v-if="href"
        :href="href"
        :class="classes"
    >
        <slot />
    </Link>

    <button v-else :class="classes">
        <slot />
    </button>
</template>

<script lang="ts" setup>
import { Link } from "@inertiajs/vue3";

withDefaults(
    defineProps<{ href?: string|null }>(),
    { href: null },
);

const classes = "bg-indigo-700 text-white rounded-md text-sm font-medium px-4 py-2 transition duration-300 hover:bg-indigo-600";
</script>
