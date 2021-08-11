<template>
    <Disclosure as="div" v-slot="{ open, close }">
        <DisclosureButton
            v-if="!open"
            class="
                flex
                w-full
                rounded
                items-center
                text-sm
                p-2
                bg-gray-100
                text-gray-600
                hover:bg-gray-200 hover:text-gray-800
            "
        >
            <PlusIcon class="w-4 h-4 text-gray-500 mr-1" />
            Add item
        </DisclosureButton>

        <div v-else class="flex items-center justify-between">
            <LoadingButton
                :loading="form.processing"
                class="w-auto px-4"
                :form="`palette_form_${type}`"
            >
                Add Item
            </LoadingButton>

            <DisclosureButton>
                <XIcon class="w-6 h-6 text-gray-400" />
            </DisclosureButton>
        </div>

        <DisclosurePanel>
            <form @submit.prevent="submit(close)" :id="`palette_form_${type}`">
                <textarea
                    v-model="form.name"
                    v-focus
                    class="
                        text-gray-800
                        px-3
                        py-2
                        rounded-md
                        shadow-sm
                        border border-gray-200
                        text-sm
                        w-full
                        resize-none
                        mt-2
                    "
                    @keydown.enter.prevent="submit(close)"
                    @keyup.esc="test"
                    placeholder="Enter a description"
                ></textarea>
            </form>
        </DisclosurePanel>
    </Disclosure>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { PlusIcon, XIcon } from "@heroicons/vue/solid";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { useForm } from "@inertiajs/inertia-vue3";

import LoadingButton from "@/components/LoadingButton.vue";

export default defineComponent({
    name: "PaletteItemForm",

    props: {
        type: String,
        history: Object,
    },

    components: {
        LoadingButton,
        PlusIcon,
        XIcon,
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
    },

    methods: {
        test() {
            alert("aaa");
        },
    },

    setup(props) {
        const form = useForm({
            name: "",
            type: props.type,
        });
        const submit = (close) => {
            form.post(route("history.palette.store", props.history), {
                only: ["errors", "palettes"],
                onSuccess: () => {
                    form.reset();
                    close();
                },
            });
        };

        return { form, submit };
    },
});
</script>
