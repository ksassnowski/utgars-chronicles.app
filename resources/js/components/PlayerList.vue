<template>
    <div>
        <h3 class="font-bold text-sm text-gray-700 mb-4">Players</h3>
        <ul>
            <li
                v-for="player in sortedPlayers"
                :key="player.id"
                class="bg-white px-2 py-1 border-l-4 border-green-400 mb-2 text-sm shadow"
            >
                {{ player.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import { sortBy } from "lodash";

export default {
    name: "PlayerList",

    props: ["channel"],

    computed: {
        sortedPlayers() {
            return sortBy(this.players, "name");
        }
    },

    data() {
        return {
            players: []
        };
    },

    created() {
        Echo.join(this.channel)
            .here(users => {
                this.players = users;
            })
            .joining(user => {
                this.players.push(user);
            })
            .leaving(user => {
                this.players = this.players.filter(p => p.id !== user.id);
            });
    }
};
</script>
