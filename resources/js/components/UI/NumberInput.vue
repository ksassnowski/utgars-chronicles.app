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

<script>
import InputGroup from "./InputGroup";
export default {
    name: "NumberInput",

    components: {
        InputGroup,
    },

    props: {
        name: String,
        required: {
            type: Boolean,
            default: false,
        },
        placeholder: String,
        value: String,
        min: Number,
        max: Number,
        step: Number
    },

    computed: {
        text: {
            get() {
                return this.value;
            },

            set(val) {
                this.$emit('input', val);
            },
        },
    }
}
</script>
