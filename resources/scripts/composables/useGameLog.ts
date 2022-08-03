import { ref } from "vue";

type Message = {
    id: string;
    title: string;
    message: string;
    icon: string;
    close: () => void;
};

export const useGameLog = (function () {
    const messages = ref([] as Message[]);
    const removeMessage = (id: string) => {
        messages.value = messages.value.filter((m) => m.id !== id);
    };
    const addMessage = (
        message: Pick<Message, "title" | "icon" | "message">
    ) => {
        const id = Math.random()
            .toString(36)
            .replace(/[^a-z]+/g, "")
            .substring(0, 10);

        const close = () => removeMessage(id);
        messages.value.push({ id, close, ...message });

        setTimeout(close, 4000);

        return close;
    };

    return () => ({ messages, addMessage });
})();
