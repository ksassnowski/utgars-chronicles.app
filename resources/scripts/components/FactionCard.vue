<template>
    <div class="px-5 py-4 shadow-md shadow-indigo-100 rounded ring-1 ring-indigo-50 relative group">
        <button
            v-if="!editing"
            @click="editing = true"
            class="sm:invisible group-hover:visible p-1.5 rounded transition duration-150 hover:bg-black/10 absolute top-2 right-2"
            title="Edit faction"
        >
            <PencilIcon class="w-4 h-4 text-gray-600" />
        </button>

        <button
            v-else
            @click="cancel"
            class="absolute right-2 top-2 p-1.5"
            title="Cancel editing faction"
        >
            <XIcon class="w-4 h-4 text-gray-400" />
        </button>

        <form @submit.prevent="submit" v-if="editing" class="space-y-2">
            <div>
                <InputLabel for="name">Name</InputLabel>

                <TextInput
                    v-model="form.name"
                    class="mt-1"
                    name="name"
                />
            </div>

            <div>
                <InputLabel for="description">Goal</InputLabel>

                <TextareaInput
                    v-model="form.description"
                    class="mt-1"
                    name="description"
                    rows="5"
                />
            </div>

            <footer class="flex justify-end">
                <LoadingButton type="submit" :loading="form.processing">
                    Save
                </LoadingButton>
            </footer>
        </form>

        <template v-else>
            <p class="text-center font-serif text-xl">
                {{ faction === "1" ? "I" : "II" }}
            </p>

            <p class="text-center font-bold text-gray-800 mt-1">
                {{ name }}
            </p>

            <hr class="my-2">

            <p
                class="text-center text-sm text-gray-700 whitespace-pre-wrap"
            >{{ description }}</p>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import { PencilIcon, XIcon } from "@heroicons/vue/outline";
import { useForm } from "@inertiajs/inertia-vue3";

import { History } from "@/types";
import InputLabel from "@/components/UI/InputLabel.vue";
import TextInput from "@/components/UI/TextInput.vue";
import TextareaInput from "@/components/UI/TextareaInput.vue";
import LoadingButton from "@/components/LoadingButton.vue";

const props = defineProps<{
    history: History,
    faction: "1"|"2",
    name: string|null,
    description: string|null,
}>();

const editing = ref(false);

const form = useForm({
    name: props.name,
    description: props.description,
});

const submit = () =>
    form
        .transform(({ name, description}) => ({
            [`faction_${props.faction}_name`]: name,
            [`faction_${props.faction}_description`]: description,
        }))
        .patch(
            route('history.echo-settings.update', props.history),
            {
                onSuccess: () => editing.value = false,
            },
        );

const cancel = () => {
    form.name = props.name;
    form.description = props.description;
    editing.value = false;
};
</script>
