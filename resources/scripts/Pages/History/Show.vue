<template>
    <Head :title="history.name" />

    <div class="mx-auto lg:max-w-5xl px-4">
        <PageHeader>{{ history.name }}</PageHeader>

        <Panel class="mt-4">
            <section class="py-6 border-b border-gray-200">
                <div class="px-6 flex">
                    <div class="w-1/3 pr-4">
                        <h2 class="text-base text-gray-700">Play</h2>
                    </div>

                    <div class="w-2/3">
                        <PrimaryButton as="a" :href="route('history.play', history)">
                            Join Game
                        </PrimaryButton>
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
                                {{ $page.props.auth.user.name }} (owner)
                            </li>
                            <li
                                v-for="player in history.players"
                                :key="player.id"
                                class="mt-1 group"
                            >
                                {{ player.name }}
                                <button
                                    @click="onClickKickPlayer(player)"
                                    class="
                                        invisible
                                        group-hover:visible
                                        inline
                                        text-sm text-red-600
                                        ml-2
                                    "
                                >
                                    {{
                                        confirmKickPlayer
                                            ? "Really kick this player?"
                                            : "Kick"
                                    }}
                                </button>
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
                        <GameVisibilityBadge :public="history.public" />

                        <div class="mb-4 mt-2">
                            <label for="link" class="label">Invite Link</label>

                            <div class="relative flex space-x-0.5">
                                <TextInput
                                    type="text"
                                    name="link"
                                    class="z-0 text-sm rounded-r-none"
                                    v-model="invitationLink"
                                    readonly
                                />

                                <div
                                    class="flex-1 flex items-center justify-center z-10"
                                >
                                    <CopyToClipboard :contents="invitationLink" />
                                </div>
                            </div>

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
                                class="bg-indigo-700 text-white rounded-md text-sm font-medium px-4 py-2 transition duration-300 hover:bg-indigo-600"
                                :href="route('history.export', history)"
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
                            <PrimaryButton @click="onClickChangeVisibility">
                                {{
                                    confirmChangeVisibility
                                        ? "Click again to confirm"
                                        : visibilityButtonText
                                }}
                            </PrimaryButton>

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

                        <button
                            class="px-4 py-2 bg-red-700 rounded-md text-white text-sm font-medium transition duration-300 hover:bg-red-600"
                            @click="onClickDeleteHistory"
                        >
                            {{
                                confirmDeleteHistory
                                    ? "Are you sure?"
                                    : "Delete history"
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
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/inertia-vue3";

import { useConfirmAction } from "@/composables/useConfirmAction";
import GameVisibilityBadge from "@/components/GameVisibilityBadge.vue";
import Panel from "@/components/UI/Panel.vue";
import PageHeader from "@/components/UI/PageHeader.vue";
import PrimaryButton from "@/components/UI/PrimaryButton.vue";
import TextInput from "@/components/UI/TextInput.vue";
import CopyToClipboard from "@/components/UI/CopyToClipboard.vue";

interface History {
    id: number;
    name: string;
    public: boolean;
}

const props = defineProps<{ history: History, invitationLink: string}>() ;

const visibilityButtonText = computed(() => {
    return props.history.public
            ? "Make game private"
            : "Make game public";
})

const {
    needsConfirmation: confirmChangeVisibility,
    onClick: onClickChangeVisibility,
} = useConfirmAction(() => {
    Inertia.patch(route("history.visibility", props.history), {
        public: !props.history.public,
    });
});

const {
    needsConfirmation: confirmKickPlayer,
    onClick: onClickKickPlayer,
} = useConfirmAction((player) =>
    Inertia.delete(
        route("history.players.kick", [props.history, player])
    )
);

const {
    needsConfirmation: confirmDeleteHistory,
    onClick: onClickDeleteHistory,
} = useConfirmAction(() =>
    Inertia.delete(route("history.delete", props.history))
);

const saveInvitationLinkToClipboard = () =>
    navigator.clipboard.writeText(props.invitationLink);
</script>
