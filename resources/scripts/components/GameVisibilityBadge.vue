<template>
    <div
        class="
            rounded-md
            text-xs
            px-2
            py-1
            font-medium
            inline-flex
            items-center
        "
        :class="{
            'bg-teal-100 text-green-800': public,
            'bg-amber-100 text-amber-800': !public,
        }"
    >
        <component
            :is="public ? LockOpenIcon : LockClosedIcon"
            class="w-4 h-4 mr-1"
            :class="public ? 'text-teal-500' : 'text-amber-500'"
        />
        {{ public ? "Public" : "Private" }}
    </div>
</template>

<script lang="ts" setup>
import { LockOpenIcon, LockClosedIcon } from "@heroicons/vue/outline";

defineProps<{ public: boolean }>();
</script>
