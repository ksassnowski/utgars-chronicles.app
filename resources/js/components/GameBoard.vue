<template>
    <div class="flex flex-col justify-between flex-1">
        <Modal v-if="showModal" title="Add Period" @close="showModal = false">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="label" ref="input">Name</label>
                    <textarea type="text" class="input" id="name" v-model="form.name" rows="5" required></textarea>
                </div>

                <div class="mb-4">
                    <p class="label">Tone</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <input type="radio" id="light" value="light" v-model="form.type">
                            <label for="light">Light</label>
                        </div>

                        <div>
                            <input type="radio" id="dark" value="dark" v-model="form.type">
                            <label for="dark">Dark</label>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="text-white w-full rounded py-2 px-4"
                    :class="{ 'bg-indigo-400 cursor-not-allowed': loading, 'bg-indigo-700 ': !loading }"
                    :disabled="loading"
                >
                    {{ loading ? 'Hang on...' : 'Save' }}
                </button>
            </form>
        </Modal>

        <div class="flex flex-1 flex-col">
            <div class="flex items-center px-4 mb-4">
                <div class="flex-1">
                    <InertiaLink :href="$route('home')" class="text-gray-800 font-semibold">&laquo; back</InertiaLink>
                </div>

                <HistorySeed :history="internalHistory" />

                <div class="flex-1 flex justify-end">
                    <button
                        class="px-4 py-2 bg-indigo-700 rounded text-white font-bold"
                        @click="() => create(lastPosition + 1)"
                    >Add Period</button>
                </div>
            </div>

            <div class="flex flex-1" id="board">
                <draggable
                    :list="orderedPeriods"
                    @change="onPeriodMoved"
                    handle=".handle"
                    class="px-4 flex w-full h-full pt-4 pb-64"
                    :class="{ 'overflow-auto': !panningEnabled }"
                >
                    <PeriodCard
                        v-for="period in orderedPeriods"
                        :period="period"
                        :key="period.id"
                        :history-id="history.id"
                        @insertPeriod="create"
                    />
                </draggable>
            </div>
        </div>

        <GamePanel>
            <div class="pt-4 flex -mx-4">
                <FocusTracker
                    class="w-1/4 px-4"
                    :channel="channelName"
                    :foci="history.foci"
                    :history-id="history.id"
                />

                <Palette
                    class="w-1/3 px-4"
                    :channel="channelName"
                    :palette="history.palettes"
                    :history-id="history.id"
                />

                <LegacyTracker
                    class="w-1/4 px-4"
                    :channel="channelName"
                    :legacies="history.legacies"
                    :history-id="history.id"
                />

                <PlayerList
                    :channel="channelName"
                    class="flex-grow px-4"
                />
            </div>
        </GamePanel>
    </div>
</template>

<script>
import axios from 'axios';
import sortBy from 'lodash/sortBy';
import each from 'lodash/each';
import draggable from 'vuedraggable';
import Panzoom from '@panzoom/panzoom';

import PlayerList from "./PlayerList.vue";
import PeriodCard from "./PeriodCard.vue";
import FocusTracker from "./FocusTracker.vue";
import Palette from "./Palette.vue";
import LegacyTracker from "./LegacyTracker.vue";
import Modal from "./Modal.vue";
import GamePanel from "./GamePanel.vue";
import HistorySeed from "./HistorySeed.vue";

export default {
    name: 'GameBoard',

    props: {
        history: {
            type: Object,
            required: true,
        },
    },

    components: {
        HistorySeed,
        GamePanel,
        Modal,
        LegacyTracker,
        draggable,
        PeriodCard,
        PlayerList,
        FocusTracker,
        Palette,
    },

    data() {
        return {
            panzoom: null,
            internalHistory: this.history,
            periods: this.history.periods,
            showModal: false,
            loading: false,
            form: {
                name: null,
                type: 'light',
                position: this.lastPosition,
            },
        };
    },

    provide() {
        return {
            history: this.history,
        };
    },

    computed: {
        lastPosition() {
            if (this.orderedPeriods.length === 0) {
                return 0;
            }

            return this.orderedPeriods.slice(-1)[0].position;
        },

        panningEnabled() {
            const urlParams = new URLSearchParams(window.location.search);

            return urlParams.get('pan') === '1';
        },

        orderedPeriods() {
            return sortBy(this.periods, ['position']);
        },

        channelName() {
            return `history.${this.history.id}`;
        }
    },

    methods: {
        resyncBoard() {
            axios.get(this.$route('history.sync', this.history))
                .then(({ data }) => {
                    this.internalHistory = data;
                    this.periods = data.periods;
                });
        },

        submit() {
            this.loading = true;

            axios.post(this.$route('history.periods.store', this.history), this.form)
                .then(() => {
                    this.loading = false;
                    this.showModal = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        create(position) {
            this.form.position = position;
            this.form.name = null;
            this.form.tone = 'light';
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        updateSeed({ name }) {
            this.internalHistory.name = name;
        },

        updatePeriodPositions(e) {
            const period = this.periods.find(p => p.id === e.id);

            if (!period) {
                return;
            }

            this.updatePositions(period, this.periods, e.position);
        },

        updateEventPositions({ id, period, position }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

           const matchingEvent = matchingPeriod.events.find(e => e.id === id);

            if (!matchingEvent) {
                return;
            }

            this.updatePositions(matchingEvent, matchingPeriod.events, position);
        },

        updateScenePositions({ id, period, event, position }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            const matchingEvent = matchingPeriod.events.find(e => e.id === event);

            if (!matchingEvent) {
                return;
            }

            const matchingScene = matchingEvent.scenes.find(s => s.id === id);

            if (!matchingScene) {
                return;
            }

            this.updatePositions(matchingScene, matchingEvent.scenes, position);
        },

        updatePositions(entity, list, position) {
            if (entity.position === position) {
                return;
            }

            if (position > entity.position) {
                const affectedEntities = list.filter((e) => {
                    return e.id !== entity.id
                        && e.position <= position
                        && e.position > entity.position;
                });

                each(affectedEntities, e => e.position -= 1);
            } else {
                const affectedEntities = list.filter((e) => {
                    return e.id !== entity.id
                        && e.position >= position
                        && e.position < entity.position;
                });

                each(affectedEntities, e => e.position += 1);
            }

            entity.position = position;
        },

        onPeriodMoved(e) {
            if (!e.moved) {
                return;
            }

            const { element, newIndex } = e.moved;

            this.updatePeriodPositions({ id: element.id, position: newIndex + 1 });

            axios.post(this.$route('periods.move', [this.history, element]), {
                position: newIndex + 1,
            }).catch(console.error);
        },

        onEventMoved({ period, event, position }) {
            this.updateEventPositions({ id: event.id, position, period: period.id});

            axios.post(this.$route('events.move', [this.history, event]), {
                position: position,
            }).catch(console.error);
        },

        onSceneMoved({ period, event, scene, position }) {
            this.updateScenePositions({
                id: scene.id,
                period: period.id,
                event: event.id,
                position,
            });

            axios.post(this.$route('scenes.move', [this.history, scene]), {
                position: position,
            }).catch(console.error);
        },
    },

    mounted() {
        if (this.panningEnabled) {
            this.panzoom = Panzoom(document.getElementById('board'));
        }
    },

    created() {
        Bus.$on('event.moved', this.onEventMoved);
        Bus.$on('scene.moved', this.onSceneMoved);

        Echo.join(this.channelName)
            .listen('BoardUpdated', this.resyncBoard)
            .listen('HistorySeedUpdated', this.updateSeed);
    },

    beforeDestroy() {
        Bus.$off([
            'event.moved',
            'scene.moved',
        ]);
        Echo.leave(this.channelName);

        if (this.panzoom !== null) {
            this.panzoom.destroy();
        }
    },
};
</script>
