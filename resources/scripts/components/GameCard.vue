<template>
    <div
        class="
            pt-1
            px-3
            rounded-lg
            border
            shadow-sm
            relative
            panzoom-exclude
            group
            whitespace-normal
        "
        :class="{
            'bg-linear-to-br from-gray-600 to-gray-700 text-white border-transparent':
                type === 'dark',
            'bg-linear-to-br from-white to-gray-100 text-gray-700 border-gray-200':
                type === 'light',
            'text-gray-700': type === null,
            'pb-6': $slots.footer,
        }"
    >
        <div class="flex items-center justify-between space-x-2 z-20">
            <div class="flex items-center space-x-1">
                <MenuIcon class="handle w-5 h-5 text-gray-400 cursor-move" />

                <p
                    class="text-sm font-bold leading-loose uppercase"
                    :class="{
                        'text-gray-700': type === 'light' || type === null,
                        'text-white': type === 'dark',
                    }"
                >
                    {{ label }}
                </p>
            </div>

            <div
                class="
                    flex
                    items-center
                    space-x-1
                    sm:invisible sm:group-hover:visible
                "
            >
                <slot name="menu" />
            </div>
        </div>

        <div class="pb-3 pt-1">
            <slot />
        </div>

        <div
            v-if="$slots.footer"
            class="
                flex
                justify-between
                absolute
                inset-x-0
                bottom-0
                py-2
                px-3
                sm:invisible sm:group-hover:visible
            "
        >
            <slot name="footer" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { MenuIcon } from "@heroicons/vue/solid";

const props = defineProps<{ type: string, label: string }>();
</script>
