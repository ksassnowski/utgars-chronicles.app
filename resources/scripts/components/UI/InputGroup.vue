<template>
    <label :for="name" class="block text-xs font-semibold tracking-wide text-gray-600 space-y-1">
        <span>{{ label }}</span>
        <slot></slot>
    </label>
</template>

<script>
export default {
    name: "InputGroup",

    props: {
        label: String,
        name: String,
    },
}
</script>
