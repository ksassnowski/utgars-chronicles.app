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

            <button @click="startEditing" title="Edit Seed">
                <PencilIcon class="h-5 w-5 text-gray-500 ml-2" />
            </button>
        </h1>

        <form v-else @submit.prevent="() => submit()" class="flex items-center">
            <input
                type="text"
                id="name"
                class="input"
                ref="input"
                v-model="form.name"
            />

            <button
                class="text-sm text-indigo-700 px-2"
                :disabled="form.processing"
            >
                {{ form.processing ? "Saving..." : "Save" }}
            </button>

            <button
                type="button"
                class="text-sm text-gray-500 px-2"
                @click="stopEditing"
            >
                Cancel
            </button>
        </form>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive, watch } from "vue";
import { PencilIcon } from "@heroicons/vue/outline";

import { useEditMode } from "../composables/useEditMode";
import { useForm } from "@inertiajs/inertia-vue3";

type History = { name: string };

export default defineComponent({
    name: "HistorySeed",

    components: {
        PencilIcon,
    },

    props: ["history"],

    setup(props) {
        const history = reactive(props.history);
        const form = useForm({ name: history.name });
        const { editing, startEditing, stopEditing, submit } = useEditMode(
            form,
            route("history.update-seed", history),
            ["errors", "history"],
            "patch"
        );

        watch(history, (newHistory) => {
            form.name = newHistory.name;
        });

        return {
            form,
            editing,
            startEditing,
            stopEditing,
            submit: submit(),
        };
    },
});
</script>
