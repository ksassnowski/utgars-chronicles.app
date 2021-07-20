<template>
    <div>
        <h1 v-if="!editing" class="text-2xl font-bold text-gray-800 flex items-center">
            {{ history.name }}

            <button @click="startEdit" title="Edit Seed">
                <Icon name="pencil" class="fill-current h-4 w-4 text-gray-500 ml-4" />
            </button>
        </h1>

        <form v-else @submit.prevent="submit" class="flex items-center">
            <input type="text" id="name" class="input" ref="input" v-model="form.name">

            <button class="text-sm text-indigo-700 px-2" :disabled="loading">
                {{ loading ? 'Saving...' : 'Save' }}
            </button>

            <button type="button" class="text-sm text-gray-500 px-2" @click="cancel">Cancel</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

import Icon from "./Icon.vue";

export default {
    name: 'HistorySeed',

    components: {
        Icon
    },

    props: ['history'],

    data() {
        return {
            loading: false,
            editing: false,
            form: {
                name: this.history.name,
            },
        };
    },

    methods: {
        startEdit() {
            this.form.name = this.history.name;
            this.editing = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        cancel() {
            this.form.name = this.history.name;
            this.editing = false;
        },

        submit() {
            if (this.form.name === this.history.name) {
                this.editing = false;
                return;
            }

            axios.patch(this.$route('history.update-seed', this.history), this.form)
                .then(() => this.editing = false)
                .catch(console.error)
                .finally(() => this.loading = false);
        },
    },
}
</script>
