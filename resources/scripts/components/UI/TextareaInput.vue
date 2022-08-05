<template>
    <textarea
        class="w-full bg-gray-100 px-4 py-2 border-2 border-transparent rounded focus:bg-white focus:border-indigo-700 placeholder-gray-400 sm:text-sm"
        :id="name"
        :name="name"
        @input="emit('update:modelValue', $event.target.value)"
        :value="modelValue"
    ></textarea>
</template>

<script lang="ts" setup>
defineProps<{
    name: string,
    modelValue: string|null,
}>();
const emit = defineEmits(["update:modelValue"]);
</script>
