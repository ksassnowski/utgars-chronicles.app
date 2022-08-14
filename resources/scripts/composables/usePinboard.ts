import { readonly, ref } from "vue";
import { PinnedItemType } from "@/types";

export const usePinboard = (() => {
    const items = ref<PinnedItemType[]>([]);
    const itemPinned = (type: PinnedItemType): boolean =>
        items.value.find((item) => item === type) !== undefined;

    const unpinItem = (type: PinnedItemType) =>
        items.value = items.value.filter((item) => item !== type);

    const pinItem = (type: PinnedItemType) => {
        if (itemPinned(type)) {
            return;
        }

        items.value.push(type);
    }

    return () => ({
        items: readonly(items),
        itemPinned,
        unpinItem,
        pinItem,
    });
})();
