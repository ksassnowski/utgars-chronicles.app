<template>
    <div>
        <h1
            v-if="!editing"
            class="
                text-lg
                sm:text-2xl
                font-semibold
                sm:font-bold
                text-gray-800
                flex
                items-center
            "
        >
            {{ history.name }}

            <button @click="startEdit" title="Edit Seed">
                <PencilIcon class="h-5 w-5 text-gray-500 ml-2" />
            </button>
        </h1>

        <form v-else @submit.prevent="submit" class="flex items-center">
            <input
                type="text"
                id="name"
                class="input"
                ref="input"
                v-model="form.name"
            />

            <button class="text-sm text-indigo-700 px-2" :disabled="loading">
                {{ loading ? "Saving..." : "Save" }}
            </button>

            <button
                type="button"
                class="text-sm text-gray-500 px-2"
                @click="cancel"
            >
                Cancel
            </button>
        </form>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { PencilIcon } from "@heroicons/vue/solid";
import axios from "axios";

export default defineComponent({
    name: "HistorySeed",

    components: {
        PencilIcon,
    },

    props: ["history"],

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

            axios
                .patch(
                    this.$route("history.update-seed", this.history),
                    this.form
                )
                .then(() => (this.editing = false))
                .catch(console.error)
                .finally(() => (this.loading = false));
        },
    },
});
</script>
