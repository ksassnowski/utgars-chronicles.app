<template>
    <Listbox v-model="selected">
        <div class="relative z-10">
            <ListboxButton
                class="relative w-full cursor-default border border-gray-200 rounded bg-white py-2.5 pl-3 pr-10 text-left focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-300 sm:text-sm"
            >
                <span class="block truncate">
                    {{ selected !== null ? selected[displayProperty] : 'Pick an option' }}
                </span>

                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
               </span>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions
                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="option in options"
                        :key="option[displayProperty]"
                        :value="option"
                        as="template"
                    >
                        <li
                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                            :class="[active ? 'bg-purple-100 text-indigo-900' : 'text-gray-900']"
                        >
                            <span
                                :class="[
                                selected ? 'font-medium' : 'font-normal',
                                'block truncate',
                              ]"
                            >
                                {{ option[displayProperty] }}
                            </span>

                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-400"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption
} from "@headlessui/vue";
import { SelectorIcon, CheckIcon } from "@heroicons/vue/outline";

const props = withDefaults(
    defineProps<{
        options: Array<{ label: string, value: any }>
        displayProperty?: string,
        modelValue?: any,
    }>(),
    {
        modelValue: null,
        displayProperty: "name",
    },
);
const emit = defineEmits(["update:modelValue"]);
const selected = computed({
    get: () => props.modelValue,
    set: (option: string | number) => emit("update:modelValue", option),
});
</script>
