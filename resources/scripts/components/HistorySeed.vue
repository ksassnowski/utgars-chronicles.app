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

        <form v-else @submit.prevent="submit" class="flex items-center">
            <TextInput
                type="text"
                name="name"
                ref="input"
                v-model="form.name"
                class="md:w-[22rem]"
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
import { useForm } from "@inertiajs/inertia-vue3";
import { PencilIcon } from "@heroicons/vue/outline";

import { useEditMode } from "@/composables/useEditMode";
import TextInput from "@/components/UI/TextInput.vue";

type History = { name: string };

export default defineComponent({
    name: "HistorySeed",

    components: {
        TextInput,
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
