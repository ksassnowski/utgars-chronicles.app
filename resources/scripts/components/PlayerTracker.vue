<template>

</template>

<script lang="ts" setup>
import { ref, onMounted, inject } from "vue";

import { Player } from "@/types";
import {ChannelKey} from "@/symbols";

const players = ref<Array<Player>>([]);
const channel = inject(ChannelKey);

onMounted(() => {
    Echo.join(channel)
        .here((users: Array<Player>) => players.value = users)
        .joining((user: Player) => players.value.push(user))
        .leaving((user: Player) =>
            players.value = players.value.filter(p => p.id !== user.id)
        );
})
</script>
