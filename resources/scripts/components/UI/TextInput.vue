<template>
    <input
        v-model="text"
        type="text"
        class="w-full bg-gray-100 px-4 py-2 border-2 border-transparent rounded focus:bg-white focus:border-indigo-700 placeholder-gray-400"
        :name="name"
        :id="name"
        :required="required"
        :placeholder="placeholder"
    >
</template>

<script lang="ts" setup>
import { computed } from "vue";

const props = withDefaults(
    defineProps<{
        name: string,
        required?: boolean|null;
        placeholder?: string|null,
        modelValue?: string|null,
    }>(),
    {
        required: false,
        placeholder: null,
        modelValue: null,
    },
);

const emit = defineEmits(["update:modelValue"]);

const text = computed({
    get() {
        return props.modelValue;
    },
    set(val: string) {
        emit("update:modelValue", val);
    }
});
</script>
