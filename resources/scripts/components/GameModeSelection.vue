<template>
    <RadioGroup v-model="mode">
        <RadioGroupLabel class="label">Game Mode</RadioGroupLabel>

        <div class="space-y-2 mt-1">
            <RadioGroupOption
                v-for="option in options"
                as="template"
                :value="option.value"
                v-slot="{ active, checked }"
            >
                <div
                    class="rounded-lg px-5 py-3 shadow ring-1 cursor-pointer transition duration-300 relative"
                    :class="[
                        checked ? 'ring-indigo-50 bg-indigo-800' : 'ring-gray-100',
                        active ? 'ring-2 ring-white ring-opacity-0 ring-offset-2 ring-offset-indigo-200' : '',
                    ]"
                >
                    <CheckCircleIcon
                        v-if="checked"
                        class="w-6 h-6 shrink-0 text-indigo-200 absolute right-2 top-2"
                    />

                    <RadioGroupLabel
                        as="p"
                        class="font-medium text-sm"
                        :class="checked ? 'text-white' : 'text-gray-800'"
                    >
                        {{ option.label }}
                    </RadioGroupLabel>

                    <RadioGroupDescription
                        class="text-xs"
                        :class="checked ? 'text-indigo-100' : 'text-gray-600'"
                    >
                        {{ option.description }}
                    </RadioGroupDescription>
                </div>
            </RadioGroupOption>

        </div>
    </RadioGroup>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from "@headlessui/vue";
import { CheckCircleIcon } from "@heroicons/vue/outline";

import { MicroscopeGameMode } from "@/types";

const props = withDefaults(
    defineProps<{ modelValue?: MicroscopeGameMode }>(),
    { modelValue: MicroscopeGameMode.BaseGame },
);
const emit = defineEmits(["update:modelValue"]);
const mode = computed({
    get: () => props.modelValue,
    set: (mode: MicroscopeGameMode) => emit("update:modelValue", mode),
});

const options = [
    {
        label: "Microscope",
        description: "A regular game of Microscope.",
        value: MicroscopeGameMode.BaseGame,
    },
    {
        label: "Echo",
        description: "Adds interventions, Echos, factions and contradiction tokens to the game.",
        value: MicroscopeGameMode.Echo,
    },
];
</script>
