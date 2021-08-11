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
            'bg-green-200 text-green-800': public,
            'bg-orange-200 text-orange-700': !public,
        }"
    >
        <component
            :is="public ? 'LockOpenIcon' : 'LockClosedIcon'"
            class="w-4 h-4 mr-1"
            :class="public ? 'text-green-700' : 'text-orange-600'"
        />
        {{ public ? "Public" : "Private" }}
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { LockOpenIcon, LockClosedIcon } from "@heroicons/vue/outline";

export default defineComponent({
    name: "GameVisibilityBadge",

    props: {
        public: Boolean,
    },

    components: {
        LockOpenIcon,
        LockClosedIcon,
    },
});
</script>
