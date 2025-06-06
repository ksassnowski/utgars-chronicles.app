<template>
    <div
        class="
            group
            text-gray-800
            relative
            px-3
            py-2
            rounded-md
            shadow-sm
            border border-gray-200
            text-sm
            w-full
            resize-none
        "
        :class="{ 'cursor-pointer': !editing, 'bg-gray-100': form.processing }"
        @click="startEditing"
    >
        <form v-if="editing" @submit.prevent="submitForm">
            <textarea
                v-model="form.name"
                v-focus
                ref="input"
                @keydown.enter.prevent="submitForm"
                @keyup="(event) => resizeTextarea(event.target)"
                @blur="submitForm"
                :onfocus="onFocusTextarea"
                :disabled="form.processing"
                class="
                    outline-none
                    w-full
                    resize-none
                    border-none
                    text-sm
                    bg-transparent
                    p-0
                    focus:outline-none focus:border-none focus:ring-0
                    overflow-hidden
                "
                >{{ item.name }}</textarea
            >
        </form>

        <template v-else>
            {{ item.name }}

            <Link
                as="button"
                method="delete"
                :href="route('palette.delete', [item.history_id, item])"
                :only="['errors', 'palettes']"
                @click.stop
                class="
                    absolute
                    md:invisible md:group-hover:visible
                    top-0
                    right-0
                    p-1
                    mt-1.5
                    mr-1.5
                    bg-black bg-opacity-30
                    text-gray-100
                    md:text-gray-200
                    rounded
                    hover:bg-opacity-60
                    md:hover:text-white
                    transition
                "
            >
                <TrashIcon class="w-4 h-4" />
            </Link>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { TrashIcon } from "@heroicons/vue/outline";

import { PaletteItem } from "@/types";
import { useEditMode } from "@/composables/useEditMode";

const props = defineProps<{ item: PaletteItem }>();

const input = ref(null);
const form = useForm({
    name: props.item.name,
    type: props.item.type,
});
const { editing, startEditing, stopEditing, submit } = useEditMode(
    form,
    route("palette.update", [props.item.history_id, props.item]),
    ["errors", "palettes"],
    "put"
);

const submitForm = () => {
    if (form.name === props.item.name) {
        stopEditing();
        return input.value.blur();
    }

    submit()();
};

const resizeTextarea = (textarea: HTMLTextAreaElement) => {
    textarea.style.height = "1px";
    textarea.style.height = 25 + textarea.scrollHeight + "px";
};

const onFocusTextarea = (event: FocusEvent) => {
    const textarea = event.target as HTMLTextAreaElement;
    textarea.select();
    resizeTextarea(textarea);
};
</script>
