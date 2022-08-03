import { ref } from "vue";

export function useConfirmAction(callback, timeout: number = 3000) {
    const needsConfirmation = ref(false);

    const onClick = (context) => {
        if (needsConfirmation.value) {
            callback(context);
            return;
        }

        needsConfirmation.value = true;

        setTimeout(() => needsConfirmation.value = false, timeout);
    }

    return { needsConfirmation, onClick };
}
