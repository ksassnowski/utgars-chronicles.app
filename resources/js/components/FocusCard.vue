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
                :href="$route('focus.delete', [focus.history_id, focus])"
                :only="['foci']"
            >
                <Icon name="close" class="w-3 h-3 fill-current" />
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
                <Icon name="pencil" class="w-3 h-3 fill-current" />
            </button>
        </div>

        <span v-if="!editing" class="p-6 block">
            {{ focus.name }}
        </span>

        <template v-else>
            <button @click="stopEditing" class="absolute -top-6 right-2">
                <Icon
                    name="close"
                    class="w-4 h-4 fill-current text-gray-400"
                ></Icon>
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

import { useEditMode } from "../composables/useEditMode";
import Icon from "./Icon.vue";

export default defineComponent({
    name: "FocusCard",

    components: {
        Icon,
        Link,
    },

    props: {
        focus: Object,
        buttonClasses: String,
    },

    setup(props) {
        const form = useForm({ name: props.focus.name });
        const { editing, startEditing, stopEditing, submit } = useEditMode(
            form,
            route("focus.update", [props.focus.history_id, props.focus]),
            ["errors", "foci"],
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
