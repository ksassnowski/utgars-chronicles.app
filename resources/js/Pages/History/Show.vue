<template>
    <div class="container mx-auto px-4">
        <div
            class="rounded shadow-lg py-6 border border-gray-300 lg:w-3/5 mx-auto"
        >
            <h1
                class="font-bold text-4xl text-gray-800 px-6 mb-6 sm:mb-4 leading-tight"
            >
                {{ history.name }}
            </h1>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Play</h2>
                    </div>

                    <div class="w-2/3">
                        <InertiaLink
                            class="bg-indigo-700 inline-block text-white text-lg font-bold px-8 py-3 rounded"
                            :href="$route('history.play', history)"
                        >
                            Join Game
                        </InertiaLink>
                    </div>
                </div>
            </section>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700 mb-2">Players</h2>
                        <p class="text-xs text-gray-600">
                            Note that only players with an Account on Utgar's
                            Chronicles will show up here. If you make this game
                            public, guests will show up inside the game, but not
                            on this overview.
                        </p>
                    </div>

                    <div class="w-2/3">
                        <ul>
                            <li class="italic">
                                {{ $page.auth.user.name }} (owner)
                            </li>
                            <li
                                v-for="player in history.players"
                                :key="player.id"
                                class="mt-1 group"
                            >
                                {{ player.name }}
                                <ConfirmAction @confirmed="kickPlayer(player)">
                                    <button
                                        @click="act"
                                        slot-scope="{ act, needsConfirmation }"
                                        class="invisible group-hover:visible inline text-sm text-red-600 ml-2"
                                    >
                                        {{
                                            needsConfirmation
                                                ? "Really kick this player?"
                                                : "Kick"
                                        }}
                                    </button>
                                </ConfirmAction>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">
                            Invite Players
                        </h2>
                    </div>

                    <div class="w-2/3">
                        <Badge
                            :type="history.public ? 'success' : 'warning'"
                            :icon="history.public ? 'lock-open' : 'lock-closed'"
                            class="mb-2"
                            >{{
                                history.public ? "Public game" : "Private game"
                            }}</Badge
                        >

                        <div class="mb-4">
                            <label for="link" class="label">Invite Link</label>
                            <input
                                type="text"
                                id="link"
                                class="input text-sm"
                                :value="invitationLink"
                                ref="link"
                                @click="$refs.link.select()"
                                readonly
                            />
                            <small class="text-xs text-gray-600 mt-1">
                                Send this link to the person you want to invite
                                to your game. This link is valid for
                                <strong>24 hour</strong>. Every time you refresh
                                this page, you receive a new invitation link.
                            </small>
                        </div>
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
                            <a
                                :href="$route('history.export', history)"
                                class="bg-indigo-700 inline-block text-white font-bold px-8 py-3 rounded"
                            >
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
                        <div class="mb-6">
                            <ConfirmAction @confirmed="updateVisibility">
                                <button
                                    slot-scope="{ act, needsConfirmation }"
                                    class="px-8 py-3 bg-indigo-700 rounded text-white font-bold inline-block"
                                    @click="act"
                                >
                                    {{
                                        needsConfirmation
                                            ? "Click again to confirm"
                                            : visibilityButtonText
                                    }}
                                </button>
                            </ConfirmAction>

                            <div class="mt-1">
                                <small
                                    v-if="history.public"
                                    class="text-xs text-gray-600"
                                >
                                    Making a game private means that only users
                                    with an account on Utgar's Chronicles can
                                    join the game.
                                </small>
                                <small v-else class="text-xs text-gray-600">
                                    Making a game public means that anyone with
                                    an invitation link will be able to join the
                                    game. Even users that don't have an account
                                    on Utgar's Chronicles.
                                </small>
                            </div>
                        </div>

                        <ConfirmAction @confirmed="deleteHistory">
                            <button
                                slot-scope="{ act, needsConfirmation }"
                                class="px-8 py-3 bg-red-700 rounded text-white font-bold"
                                @click="act"
                            >
                                {{
                                    needsConfirmation
                                        ? "Are you sure?"
                                        : "Delete history"
                                }}
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
import Badge from "../../components/Badge";

export default {
    name: "Show",

    metaInfo() {
        return {
            title: `${this.$page.history.name} – Utgar\'s Chronicles`
        };
    },

    props: ["history", "invitationLink"],

    layout: Layout,

    components: {
        Badge,
        ConfirmAction
    },

    computed: {
        visibilityButtonText() {
            return this.history.public
                ? "Make game private"
                : "Make game public";
        }
    },

    methods: {
        deleteHistory() {
            this.$inertia.delete(this.$route("history.delete", this.history));
        },

        updateVisibility() {
            this.$inertia.patch(
                this.$route("history.visibility", this.history),
                {
                    public: !this.history.public
                }
            );
        },

        kickPlayer(player) {
            this.$inertia.delete(
                this.$route("history.players.kick", [this.history, player])
            );
        }
    }
};
</script>
