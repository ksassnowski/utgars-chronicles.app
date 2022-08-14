<template>
    <component
        v-for="item in items"
        :key="item.type"
        :is="itemComponent(item)"
    />
</template>

<script lang="ts" setup>
import { inject } from "vue";

import { PinnedItemType } from "@/types";
import { HistoryKey } from "@/symbols";
import { usePinboard } from "@/composables/usePinboard";
import PinnedFocus from "@/components/Pinboard/PinnedFocus.vue";
import PinnedPlayerList from "@/components/Pinboard/PinnedPlayerList.vue";

const history = inject(HistoryKey);
const { items } = usePinboard();

const itemComponent = (item: PinnedItemType) => {
    switch (item) {
        case PinnedItemType.CurrentFocus:
            return PinnedFocus;
        case PinnedItemType.Players:
            return PinnedPlayerList;
        default:
            throw new Error('no component');
    }
};
</script>
