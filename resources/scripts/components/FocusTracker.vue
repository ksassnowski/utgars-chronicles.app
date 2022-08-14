<template>
    <SettingsPopover
        title="Focus Tracker"
        button-text="Focus"
        :pinnable-type="PinnedItemType.CurrentFocus"
    >
        <template #icon>
            <ExclamationCircleIcon class="w-4 h-4" />
        </template>

        <template #description>
            The Lens declares the Focus of the game, the part of the history
            you’re going to explore next.
        </template>

        <h4 class="text-sm font-medium text-gray-900">Current Focus</h4>

        <EditableCard
            v-if="currentFocus"
            :item="currentFocus"
            :update-route="route('focus.update', [history, currentFocus])"
            :delete-route="route('focus.delete', [history, currentFocus])"
            :reload-props="['errors', 'foci']"
            class="bg-indigo-700 text-white mt-3"
        />

        <div
            v-else
            class="
                border-4 border-dashed
                rounded-xl
                border-gray-300
                text-center
                p-6
                mt-2
            "
        >
            <span class="font-semibold text-gray-400"
                >No Focus defined yet</span
            >
        </div>

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
                        Define new focus
                    </div>

                    <XIcon v-if="open" class="w-4 h-4 text-gray-500 mr-2" />
                </DisclosureButton>

                <DisclosurePanel
                    as="form"
                    class="px-2 pt-1 pb-2"
                    @submit.prevent="submitNewFocusForm(close)"
                >
                    <div>
                        <label
                            for="name"
                            class="text-sm font-medium text-gray-600"
                            :class="{
                                'text-red-500': newFocusForm.errors.name,
                            }"
                            >Name</label
                        >
                        <input
                            v-model="newFocusForm.name"
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
                                'ring-2 ring-red-500': newFocusForm.errors.name,
                            }"
                        />
                        <small
                            v-if="newFocusForm.errors.name"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ newFocusForm.errors.name[0] }}
                        </small>
                    </div>

                    <LoadingButton
                        :loading="newFocusForm.processing"
                        class="mt-2"
                    >
                        Define Focus
                    </LoadingButton>
                </DisclosurePanel>
            </div>
        </Disclosure>

        <h4 class="text-sm font-medium text-gray-900 mt-12">Previous Focus</h4>

        <FocusStack :foci="previousFoci" class="mt-3" />
    </SettingsPopover>
</template>

<script lang="ts" setup>
import { watch, computed, toRefs } from "vue";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from "@headlessui/vue";
import { XIcon, PlusIcon, ExclamationCircleIcon } from "@heroicons/vue/outline";
import { useForm } from "@inertiajs/inertia-vue3";

import { Focus, History, PinnedItemType } from "@/types";
import { useGameLog } from "@/composables/useGameLog";

import LoadingButton from "@/components/LoadingButton.vue";
import FocusStack from "@/components/FocusStack.vue";
import EditableCard from "@/components/EditableCard.vue";
import SettingsPopover from "@/components/SettingsPopover.vue";

const props = defineProps<{ foci: Array<Focus>, history: History }>();

const { foci } = toRefs(props);
const newFocusForm = useForm({ name: "" });
const submitNewFocusForm = (close) => {
    newFocusForm.post(route("history.focus.define", [props.history]), {
        only: ["foci", "errors"],
        onSuccess: (page) => {
            newFocusForm.reset();
            close();
        },
    });
};
const { addMessage } = useGameLog();
const currentFocus = computed(() =>
    foci.value.length === 0 ? null : foci.value[0]
);
const previousFoci = computed(() => foci.value.slice(1));

watch(currentFocus, (newFocus, oldFocus) => {
    if (newFocus === null || newFocus.name === oldFocus?.name) {
        return;
    }

    addMessage({
        title: "Current Focus",
        message: newFocus.name,
        icon: ExclamationCircleIcon,
    });
});
</script>
