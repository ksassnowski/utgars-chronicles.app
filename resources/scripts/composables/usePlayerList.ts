import { inject, onMounted, ref } from "vue";

import { Player } from "@/types";
import { ChannelKey } from "@/symbols";

export const usePlayerList = (() => {
    const joined = ref(false);
    const players = ref<Player[]>([]);

    return () => {
        onMounted(() => {
            if (joined.value) {
                return;
            }

            const channel = inject(ChannelKey);
            Echo.join(channel)
                .here((users: Array<Player>) => players.value = users)
                .joining((user: Player) => players.value.push(user))
                .leaving((user: Player) =>
                    players.value = players.value.filter(p => p.id !== user.id)
                );

                joined.value = true;
        });

        return players;
    };
})();
