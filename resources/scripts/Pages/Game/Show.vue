<template>
    <Head :title="game.name" />

    <div class="md:max-w-5xl mx-auto px-4">
        <PageHeader>{{ game.name }}</PageHeader>

        <Panel class="mt-4">
            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Play</h2>
                    </div>

                    <div class="w-2/3">
                        <PrimaryButton :href="route('history.play', game)">
                            Join Game
                        </PrimaryButton>
                    </div>
                </div>
            </section>

            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Players</h2>
                    </div>

                    <div class="w-2/3">
                        <ul>
                            <li>{{ game.owner.name }} (owner)</li>
                            <li
                                v-for="player in game.players"
                                :key="player.id"
                                class="mt-1"
                                :class="{
                                    italic:
                                        player.id === $page.props.auth.user.id
                                }"
                            >
                                {{ player.name }}
                                {{
                                    player.id === $page.props.auth.user.id
                                        ? " (You)"
                                        : ""
                                }}
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
                            <a
                                class="bg-indigo-700 text-white rounded-md text-sm font-medium px-4 py-2 transition duration-300 hover:bg-indigo-600"
                                :href="route('history.export', game)"
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
                        <button
                            class="px-4 py-2 bg-red-700 rounded-md text-white text-sm font-medium transition duration-300 hover:bg-red-600"
                            @click="onClick"
                        >
                            {{
                                needsConfirmation
                                    ? "Are you sure?"
                                    : "Leave Game"
                            }}
                        </button>
                    </div>
                </div>
            </section>
        </Panel>
    </div>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Layout.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";

import { History } from "@/types";
import { useConfirmAction } from "@/composables/useConfirmAction";
import PageHeader from "@/components/UI/PageHeader.vue";
import Panel from "@/components/UI/Panel.vue";
import PrimaryButton from "@/components/UI/PrimaryButton.vue";

const props = defineProps<{ game: History }>();

const { needsConfirmation, onClick } = useConfirmAction(() =>
    Inertia.delete(route("user.games.leave", props.game))
);
</script>
