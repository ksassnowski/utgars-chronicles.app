<template>
    <input
        v-model="text"
        type="number"
        class="w-full bg-gray-100 px-4 py-2 border-2 border-transparent rounded focus:bg-white focus:border-indigo-700 placeholder-gray-400"
        :name="name"
        :id="name"
        :required="required"
        :placeholder="placeholder"
        :min="min"
        :max="max"
        :step="step"
    >
</template>

<script lang="ts" setup>
import { computed } from "vue";

const props = withDefaults(
    defineProps<{
        name: string,
        required?: boolean,
        placeholder: string,
        value: string,
        min: number,
        max: number,
        step: number,
    }>(),
    { required: false },
);

const emit = defineEmits(["input"]);

const text = computed({
    get() {
        return props.value;
    },
    set(value: string) {
        emit("input", value);
    }
});
</script>
