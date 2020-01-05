<template>
    <div class="container mx-auto px-4">
        <div class="rounded shadow-lg py-6 border border-gray-300 lg:w-3/5 mx-auto">
            <header class="flex justify-between items-center mb-6 px-6">
                <h1 class="font-bold text-4xl text-gray-800">{{ history.name }}</h1>

                <InertiaLink class="bg-indigo-700 text-white text-lg font-bold px-8 py-3 rounded" :href="$route('history.play', history)">
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
                            <li class="italic">{{ $page.auth.user.name }} (owner)</li>
                            <li v-for="player in history.players" :key="player.id" class="mt-1">
                                {{ player.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </section>


            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Invite Players</h2>
                    </div>

                    <div class="w-2/3">
                        <div class="mb-4">
                            <label for="link" class="label">Invite Link</label>
                            <input type="text" id="link" class="input text-sm" :value="invitationLink" ref="link" @click="$refs.link.select()" readonly>
                            <small class="text-xs text-gray-600 mt-1">Send this link to the person you want to invite to your game.</small>
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
                        <ConfirmAction @confirmed="deleteHistory">
                            <button slot-scope="{ act, needsConfirmation }" class="px-8 py-2 bg-red-700 rounded text-white font-bold" @click="act">
                                {{ needsConfirmation ? 'Are you sure?' : 'Delete history' }}
                            </button>
                        </ConfirmAction>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import Layout from '../Layouts/Layout';
import ConfirmAction from "../../components/ConfirmAction";

export default {
    name: 'Show',

    props: ['history', 'invitationLink'],

    layout: Layout,

    components: {
        ConfirmAction,
    },

    methods: {
        deleteHistory() {
            this.$inertia.delete(this.$route('history.delete', this.history));
        }
    },
};
</script>
