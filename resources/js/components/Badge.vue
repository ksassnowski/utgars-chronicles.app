<template>
    <div
        class="rounded text-sm font-medium px-3 py-1 text-white inline-flex items-center"
        :class="{
            'bg-green-600 text-white': isSuccess,
            'bg-orange-600 text-white': isWarning
        }"
    >
        <Icon
            v-if="icon !== null"
            :name="icon"
            class="h-3 w-3 fill-current mr-2"
            :class="{
                'text-green-200': isSuccess,
                'text-orange-200': isWarning
            }"
        />
        <slot></slot>
    </div>
</template>

<script>
import Icon from "./Icon.vue";

export default {
    name: "Badge",

    components: {
        Icon
    },

    props: {
        type: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            default: null
        }
    },

    computed: {
        isSuccess() {
            return this.type === "success";
        },

        isWarning() {
            return this.type === "warning";
        }
    }
};
</script>
