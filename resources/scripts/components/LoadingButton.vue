<template>
    <button
        type="submit"
        class="text-white rounded py-2 px-4 text-sm transition duration-300 text-sm font-medium"
        :class="{ 'bg-indigo-400 cursor-not-allowed': loading, 'bg-indigo-700 hover:bg-indigo-600': !loading }"
        :disabled="loading"
    >
        <slot v-if="!loading" />
        <template v-else>Hang on...</template>
    </button>
</template>

<script lang="ts" setup>
const props = defineProps<{ loading: boolean }>();
</script>
