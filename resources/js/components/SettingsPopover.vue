<template>
    <Popover v-slot="{ open }">
        <PopoverButton
            class="
                text-sm
                bg-black bg-opacity-60
                text-gray-200
                font-medium
                py-1
                px-2
                rounded-sm
                hover:bg-opacity-80
                transform
                hover:text-white hover:-translate-x-1
                transition-all
            "
        >
            {{ buttonText }}
        </PopoverButton>

        <PopoverOverlay
            class="bg-black opacity-0"
            :class="[open ? 'opacity-50 inset-0 fixed' : 'opacity-0']"
        />

        <transition
            enter-active-class="transition duration-500 ease-in-out transform-gpu"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition duration-500 ease-in-out transform-gpu"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <PopoverPanel
                class="fixed inset-y-0 right-0 bg-white shadow-xl z-20 w-[90%]"
                :class="width"
            >
                <PopoverButton
                    class="
                        text-white
                        p-3
                        absolute
                        right-full
                        top-0
                        -mr-1.5
                        sm:mr-0
                        mt-1
                    "
                >
                    <XIcon class="w-7 h-7" />
                </PopoverButton>

                <div class="h-full">
                    <div class="max-h-full flex flex-col">
                        <header
                            class="
                                bg-gradient-to-r
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

<script lang="ts">
import { defineComponent } from "vue";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
} from "@headlessui/vue";
import { XIcon } from "@heroicons/vue/solid";

export default defineComponent({
    name: "SettingsPopover",

    components: {
        Popover,
        PopoverButton,
        PopoverOverlay,
        PopoverPanel,
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        XIcon,
    },

    props: {
        title: String,
        buttonText: String,
        width: {
            type: String,
            default: "sm:w-112",
        },
    },
});
</script>
