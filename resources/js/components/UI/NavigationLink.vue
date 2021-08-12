<template>
    <Link
        :href="href"
        class="text-sm font-medium transition py-2 px-3 rounded-md"
        :class="{
            'text-white bg-gray-900': isActive,
            'text-gray-300 hover:bg-gray-700 hover:text-white': !isActive,
        }"
    >
        <slot></slot>
    </Link>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "NavigationLink",

    components: {
        Link,
    },

    props: {
        href: String,
        isActive: Boolean,
    }
});
</script>
