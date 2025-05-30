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
                class="md:w-88"
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

<script lang="ts" setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { PencilIcon } from "@heroicons/vue/outline";

import { History } from "@/types";
import { useEditMode } from "@/composables/useEditMode";

import TextInput from "@/components/UI/TextInput.vue";

const props = defineProps<{ history: History }>();

const form = useForm({ name: props.history.name });
const { editing, startEditing, stopEditing, submit: formSubmit } = useEditMode(
    form,
    route("history.update-seed", props.history),
    ["errors", "history"],
    "patch"
);
const submit = formSubmit();

watch(() => props.history.name, (name: string) => {
    form.name = name;
}, { deep: true });
</script>
