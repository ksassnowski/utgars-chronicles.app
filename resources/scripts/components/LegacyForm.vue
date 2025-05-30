<template>
    <Disclosure v-slot="{ open, close }">
        <div class="rounded-md bg-gray-100 hover:bg-gray-200 mt-2">
            <DisclosureButton
                class="
                    w-full
                    text-sm
                    flex
                    justify-between
                    items-center
                    font-medium
                    p-2
                "
            >
                <div class="inline-flex items-center">
                    <PlusIcon class="w-4 h-4 text-gray-500 mr-2" />
                    Create new legacy
                </div>

                <XIcon
                    v-if="open"
                    name="close"
                    class="w-4 h-4 fill-current text-gray-500 mr-2"
                />
            </DisclosureButton>

            <DisclosurePanel
                as="form"
                class="px-2 pt-1 pb-2"
                @submit.prevent="submit(close)"
            >
                <div>
                    <label
                        for="name"
                        class="text-sm font-medium text-gray-600"
                        :class="{
                            'text-red-500': form.errors.name,
                        }"
                        >Name</label
                    >
                    <input
                        v-model="form.name"
                        v-focus
                        @keydown.esc.stop
                        name="name"
                        class="
                            w-full
                            rounded
                            py-2
                            px-4
                            bg-white
                            focus:ring-2 focus:ring-indigo-600
                        "
                        :class="{
                            'ring-2 ring-red-500': form.errors.name,
                        }"
                    />
                    <small
                        v-if="form.errors.name"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.name[0] }}
                    </small>
                </div>

                <LoadingButton :loading="form.processing" class="mt-2">
                    Create Legacy
                </LoadingButton>
            </DisclosurePanel>
        </div>
    </Disclosure>
</template>

<script lang="ts" setup>
import { Disclosure, DisclosurePanel, DisclosureButton } from "@headlessui/vue";
import { useForm } from "@inertiajs/vue3";
import { PlusIcon, XIcon } from "@heroicons/vue/solid";

import { History } from "@/types";
import LoadingButton from "@/components/LoadingButton.vue";

const props = defineProps<{ history: History }>();

const form = useForm({ name: "" });

const submit = (close: () => void) =>
    form.post(
        route("history.legacies.store", props.history),
        {
            only: ["errors", "legacies"],
            onSuccess: () => {
                form.reset();
                close();
            },
        }
    );
</script>
