<template>
    <label
        class="text-xs font-semibold tracking-wide"
        :class="hasErrors ? 'text-red-500' : 'text-gray-600'"
    >
        <slot />
    </label>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{ hasErrors?: boolean }>(),
    { hasErrors: false },
);
</script>
