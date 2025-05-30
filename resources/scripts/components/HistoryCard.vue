<template>
    <Link
        :href="url"
        class="px-8 py-6 rounded-lg shadow-lg flex flex-col items-center justify-center h-full bg-indigo-700"
    >
        <h4 class="font-bold text-xl text-white text-center">
            {{ history.name }}
        </h4>
    </Link>
</template>

<script lang="ts" setup>
import { Link } from "@inertiajs/vue3";

defineProps<{ history: History, url: string }>();
</script>
