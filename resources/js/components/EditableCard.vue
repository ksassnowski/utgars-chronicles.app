<template>
    <div
        class="shadow-sm rounded-lg text-center font-medium z-10 relative group"
    >
        <div
            v-if="!editing"
            class="flex absolute top-0 right-0 space-x-1 mr-2 mt-2"
        >
            <Link
                as="button"
                class="
                    rounded-md
                    bg-black bg-opacity-30
                    invisible
                    group-hover:visible
                    p-2
                    hover:bg-opacity-50
                    text-gray-100
                    hover:text-white
                "
                method="DELETE"
                :href="deleteRoute"
                :only="reloadProps"
            >
                <TrashIcon class="w-4 h-4" />
            </Link>

            <button
                class="
                    rounded-md
                    bg-black bg-opacity-30
                    invisible
                    group-hover:visible
                    p-2
                    hover:bg-opacity-50
                    text-gray-100
                    hover:text-white
                "
                @click="startEditing"
            >
                <PencilIcon class="w-4 h-4" />
            </button>
        </div>

        <span v-if="!editing" class="p-6 block">
            {{ item.name }}
        </span>

        <template v-else>
            <button @click="stopEditing" class="absolute -top-7 right-2">
                <XIcon class="w-5 h-5 text-gray-400"></XIcon>
            </button>

            <form @submit.prevent="submit()">
                <div class="px-6 pt-6">
                    <label for="name" class="sr-only">Name</label>
                    <input
                        v-model="form.name"
                        v-focus
                        @keydown.esc.stop
                        id="name"
                        class="
                            w-full
                            rounded
                            px-4
                            py-2
                            bg-white
                            text-gray-800
                            ring-2 ring-gray-300
                            focus:ring-blue-300
                            outline-none
                            disabled:bg-gray-200
                        "
                        :disabled="form.processing"
                    />
                </div>

                <div class="p-1.5 mt-2">
                    <button
                        class="
                            w-full
                            rounded-md
                            px-2
                            py-1.5
                            text-sm
                            font-medium
                        "
                        :class="buttonClasses"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? "Saving..." : "Save" }}
                    </button>
                </div>
            </form>
        </template>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { TrashIcon, PencilIcon, XIcon } from "@heroicons/vue/solid";

import { useEditMode } from "../composables/useEditMode";

export default defineComponent({
    name: "EditableCard",

    components: {
        Link,
        TrashIcon,
        PencilIcon,
        XIcon,
    },

    props: {
        item: Object,
        updateRoute: String,
        deleteRoute: String,
        reloadProps: Array,
        buttonClasses: String,
    },

    setup(props) {
        const form = useForm({ name: props.item.name });
        const { editing, startEditing, stopEditing, submit } = useEditMode(
            form,
            props.updateRoute,
            props.reloadProps,
            "put"
        );

        const buttonClasses =
            props.buttonClasses ||
            "bg-indigo-500 bg-opacity-70 hover:bg-opacity-60 text-gray-100 hover:text-white";

        return {
            form,
            editing,
            startEditing,
            stopEditing,
            submit,
            buttonClasses,
        };
    },
});
</script>
