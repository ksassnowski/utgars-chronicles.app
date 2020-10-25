<template>
    <div
        class="rounded text-sm font-semibold px-3 py-1 tracking-wide text-white inline-flex items-center"
        :class="{
            'bg-green-600 text-green-100': isSuccess,
            'bg-orange-600 text-orange-100': isWarning
        }"
    >
        <Icon
            v-if="icon !== null"
            :name="icon"
            class="h-4 fill-current mr-2"
            :class="{
                'text-green-300': isSuccess,
                'text-orange-300': isWarning
            }"
        />
        <slot></slot>
    </div>
</template>

<script>
import Icon from "./Icon";

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
