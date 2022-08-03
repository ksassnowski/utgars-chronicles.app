<template>
    <slot name="button" :toggle="toggle"></slot>

    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" @close="toggle">
            <div class="fixed inset-0 z-30 overflow-y-auto">
                <div class="min-h-screen px-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-black bg-opacity-50"
                        />
                    </TransitionChild>

                    <span
                        class="inline-block h-screen align-middle"
                        aria-hidden="true"
                    >
                        &#8203;
                    </span>

                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <div
                            class="
                                inline-block
                                w-full
                                max-w-xl
                                p-5
                                my-6
                                overflow-hidden
                                text-left
                                align-middle
                                transition-all
                                transform
                                bg-white
                                shadow-xl
                                rounded-lg
                            "
                        >
                            <div class="flex items-center justify-between">
                                <DialogTitle
                                    as="h3"
                                    class="font-bold text-lg text-gray-800"
                                >
                                    {{ title }}
                                </DialogTitle>

                                <button @click="toggle">
                                    <XIcon class="w-6 h-6 text-gray-400" />
                                </button>
                            </div>

                            <div class="mt-4">
                                <slot />
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import {
    Dialog,
    DialogTitle,
    DialogOverlay,
    TransitionRoot,
    TransitionChild,
} from "@headlessui/vue";
import { XIcon } from "@heroicons/vue/outline";

export default defineComponent({
    name: "Modal",

    props: {
        title: String,
    },

    emits: ["close"],

    components: {
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionRoot,
        TransitionChild,
        XIcon,
    },

    setup() {
        const isOpen = ref(false);
        const toggle = () => (isOpen.value = !isOpen.value);

        return { isOpen, toggle };
    },
});
</script>