<template>
    <div class="container mx-auto px-4">
        <div class="rounded shadow-lg py-6 border border-gray-300 lg:w-3/5 mx-auto">
            <header class="flex justify-between items-center mb-6 px-6">
                <h1 class="font-bold text-4xl text-gray-800">{{ game.name }}</h1>

                <InertiaLink class="bg-indigo-700 text-white text-lg font-bold px-8 py-3 rounded" :href="$route('history.play', game)">
                    Join Game
                </InertiaLink>
            </header>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Players</h2>
                    </div>

                    <div class="w-2/3">
                        <ul>
                            <li>{{ game.owner.name }} (owner)</li>
                            <li v-for="player in game.players" :key="player.id" class="mt-1" :class="{ italic: player.id === $page.auth.user.id }">
                                {{ player.name }} {{ player.id === $page.auth.user.id ? ' (You)' : '' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Export Game</h2>
                    </div>

                    <div class="w-2/3">
                        <div class="mb-4">
                            <a :href="$route('history.export', game)" class="bg-indigo-700 inline-block text-white font-bold px-8 py-3 rounded">
                                Export as CSV
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-6">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Danger Zone</h2>
                    </div>

                    <div class="w-2/3">
                        <ConfirmAction @confirmed="leaveGame">
                            <button slot-scope="{ act, needsConfirmation }" class="px-8 py-2 bg-red-700 rounded text-white font-bold" @click="act">
                                {{ needsConfirmation ? 'Are you sure?' : 'Leave Game' }}
                            </button>
                        </ConfirmAction>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout";
import ConfirmAction from "../../components/ConfirmAction";

export default {
    name: "ShowGame",

    layout: Layout,

    props: ['game'],

    components: {
        ConfirmAction,
    },

    methods: {
        leaveGame() {
            this.$inertia.delete(this.$route('user.games.leave', this.game));
        },
    },
};
</script>
