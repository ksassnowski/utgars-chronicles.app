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

<script>
import InputGroup from "./InputGroup";
export default {
    name: "TextInput",

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
