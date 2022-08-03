<template>
    <div class="relative" v-bind="$attrs">
        <div
            v-if="showStack"
            class="absolute inset-0 flex flex-col justify-end items-center z-0"
        >
            <div
                v-if="foci.length >= 3"
                class="px-2 absolute -bottom-2 inset-x-0"
            >
                <div
                    class="
                        border border-gray-200
                        rounded-lg
                        bg-white
                        h-4
                        w-full
                    "
                ></div>
            </div>

            <div class="px-1 absolute -bottom-1 inset-x-0">
                <div
                    class="
                        border border-gray-200
                        rounded-lg
                        bg-white
                        h-4
                        w-full
                    "
                ></div>
            </div>
        </div>

        <div class="space-y-2">
            <EditableCard
                v-for="focus in visibleFoci"
                :item="focus"
                :update-route="
                    route('focus.update', [focus.history_id, focus])
                "
                :delete-route="
                    route('focus.delete', [focus.history_id, focus])
                "
                :reload-props="['errors', 'foci']"
                :key="focus.id"
                class="bg-white text-gray-800 border border-gray-200"
                button-classes="bg-gray-200 hover:bg-gray-300 text-gray-700 hover:text-gray-900"
            />
        </div>
    </div>

    <div v-if="foci.length > 1" class="text-right mt-1">
        <button
            class="
                inline-block
                px-2
                py-2
                text-gray-500
                hover:text-gray-600
                text-sm
                font-medium
            "
            @click="toggle"
        >
            {{ open ? "Collapse" : "Show all" }}
        </button>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, toRefs } from "vue";

import EditableCard from "./EditableCard.vue";

export default defineComponent({
    name: "FocusStack",

    components: {
        EditableCard,
    },

    props: {
        foci: {
            type: Array,
            default: () => [],
        },
    },

    setup(props) {
        const open = ref(false);
        const { foci } = toRefs(props);
        const visibleFoci = computed(() => {
            if (foci.value.length === 0) {
                return [];
            }

            return open.value ? foci.value : [foci.value[0]];
        });
        const toggle = () => (open.value = !open.value);
        const showStack = computed(() => !open.value && foci.value.length > 1);

        return { open, toggle, visibleFoci, showStack };
    },
});
</script>
