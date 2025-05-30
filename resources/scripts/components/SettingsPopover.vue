<template>
    <Popover v-slot="{ open }" class="h-full sm:h-auto sm:w-full">
        <PopoverButton
            class="
                px-3
                sm:py-3 sm:bg-black/20
                text-gray-100
                h-full
                sm:w-full
                text-[0.6rem]
                sm:text-xs
                sm:uppercase
                font-medium
                rounded-md
                sm:hover:bg-black/30 sm:hover:text-white
                transition
                transform
                sm:hover:-translate-x-0.5
                flex flex-col justify-center items-center
                sm:block
                space-y-0.5
                sm:space-y-0
            "
        >
            <span class="sm:hidden text-indigo-100">
                <slot name="icon" />
            </span>

            <span>{{ buttonText }}</span>
        </PopoverButton>

        <PopoverOverlay
            class="bg-black opacity-0"
            :class="[open ? 'opacity-50 inset-0 fixed' : 'opacity-0']"
        />

        <transition
            enter-active-class="transition duration-500 ease-in-out transform-gpu"
            enter-from-class="translate-y-full sm:translate-y-0 sm:translate-x-full"
            enter-to-class="translate-y-0 sm:translate-x-0"
            leave-active-class="transition duration-500 ease-in-out transform-gpu"
            leave-from-class="translate-y-0 sm:translate-x-0"
            leave-to-class="translate-y-full sm:translate-y-0 sm:translate-x-full"
        >
            <PopoverPanel
                class="
                    fixed
                    inset-y-0
                    right-0
                    bg-white
                    shadow-xl
                    z-30
                    mt-12
                    sm:mt-0
                "
                :class="width"
            >
                <PopoverButton
                    class="
                        text-white
                        p-3
                        absolute
                        right-0
                        sm:right-full sm:top-0
                        bottom-full
                        sm:bottom-auto sm:mt-1
                    "
                >
                    <XIcon class="w-7 h-7" />
                </PopoverButton>

                <div class="h-full">
                    <div class="max-h-full flex flex-col">
                        <header
                            class="
                                bg-linear-to-r
                                from-indigo-600
                                to-indigo-800
                                py-6
                                px-6
                            "
                        >
                            <h3 class="font-medium text-lg text-white">
                                {{ title }}
                            </h3>

                            <p class="text-sm text-indigo-300 mt-1">
                                <slot name="description" />
                            </p>
                        </header>

                        <section class="p-6 flex-1 overflow-y-auto">
                            <slot />
                        </section>
                    </div>
                </div>
            </PopoverPanel>
        </transition>
    </Popover>
</template>

<script lang="ts" setup>
import {
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
} from "@headlessui/vue";
import { XIcon } from "@heroicons/vue/solid";

withDefaults(
    defineProps<{
        title: string,
        buttonText: string,
        width?: string,
    }>(),
    { width: "w-full sm:w-md" }
);
</script>
