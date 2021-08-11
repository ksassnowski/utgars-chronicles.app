<template>
    <div
        class="
            px-3
            rounded-lg
            border
            mb-4
            shadow-sm
            relative
            panzoom-exclude
            group
            whitespace-normal
        "
        :class="{
            'bg-gradient-to-br from-gray-600 to-gray-700 text-white border-transparent':
                type === 'dark',
            'bg-gradient-to-br from-white to-gray-100 text-gray-700 border-gray-200':
                type === 'light',
            'pt-5 pb-6': $slots.footer,
            'pt-5': !$slots.footer,
        }"
    >
        <p
            class="
                absolute
                top-1
                right-2.5
                text-sm
                font-bold
                leading-loose
                uppercase
                px-1
            "
            :class="{
                'text-gray-700': type === 'light',
                'text-white': type === 'dark',
            }"
        >
            {{ label }}
        </p>

        <div
            class="
                sm:invisible sm:group-hover:visible
                absolute
                left-0
                top-0
                w-full
                pl-3
                pr-2
                pt-2
                flex
                space-x-2
                z-20
            "
        >
            <MenuIcon class="handle w-5 h-5 text-gray-400 cursor-move" />

            <slot name="menu-left" />
        </div>

        <div class="pb-3 pt-3">
            <slot />
        </div>

        <div
            v-if="$slots.footer"
            class="
                flex
                justify-end
                absolute
                inset-x-0
                bottom-0
                p-2
                sm:invisible sm:group-hover:visible
            "
        >
            <slot name="footer" />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { MenuIcon } from "@heroicons/vue/solid";

export default defineComponent({
    name: "GameCard",

    props: {
        type: String,
        label: String,
    },

    components: {
        MenuIcon,
    },
});
</script>
