<template>
    <SettingsPopover title="Palette" button-text="Palette" width="w-128">
        <template #description>
            The Palette is a list of things the players agree to reserve the
            right to include or, conversely, outright ban. It gets everyone on
            the same page about what belongs in the history and what doesn’t.
        </template>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <h4
                    class="
                        text-lg
                        font-medium
                        text-gray-800
                        inline-flex
                        items-center
                    "
                >
                    <ThumbUpIcon class="w-5 h-5 text-green-500 mr-1" />
                    Yes
                </h4>

                <PaletteItemForm :history="history" type="yes" class="mt-2" />

                <div class="mt-2 space-y-2">
                    <PaletteItem
                        v-for="item in items.yes"
                        :key="item.id"
                        :item="item"
                    />
                </div>
            </div>

            <div>
                <h4
                    class="
                        text-lg
                        font-medium
                        text-gray-800
                        inline-flex
                        items-center
                    "
                >
                    <ThumbDownIcon class="w-5 h-5 text-red-400 mr-1" />
                    No
                </h4>

                <PaletteItemForm :history="history" type="no" class="mt-2" />

                <div class="mt-2 space-y-3">
                    <PaletteItem
                        v-for="item in items.no"
                        :key="item.id"
                        :item="item"
                    />
                </div>
            </div>
        </div>
    </SettingsPopover>
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { ThumbUpIcon, ThumbDownIcon } from "@heroicons/vue/solid";

import SettingsPopover from "@/components/SettingsPopover.vue";
import LoadingButton from "@/components/LoadingButton.vue";
import PaletteItemForm from "@/components/PaletteItemForm.vue";
import PaletteItem from "@/components/PaletteItem.vue";

export default defineComponent({
    name: "Palette",

    props: {
        palette: Array,
        history: Object,
    },

    components: {
        PaletteItem,
        PaletteItemForm,
        LoadingButton,
        SettingsPopover,
        ThumbUpIcon,
        ThumbDownIcon,
    },

    setup(props) {
        const partitionedItems = computed(() => {
            return props.palette.reduce(
                (result, item) => {
                    if (item.type === "yes") {
                        result.yes.push(item);
                    } else {
                        result.no.push(item);
                    }

                    return result;
                },
                { yes: [], no: [] }
            );
        });

        return { items: partitionedItems };
    },
});
</script>
