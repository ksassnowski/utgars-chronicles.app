<template>
    <div class="h-full flex flex-col">
        <div class="flex items-center w-full px-4 mb-4">
            <div class="flex-1">
                <Link :href="$route('home')" class="text-gray-800 font-semibold">&laquo; back</Link>
            </div>

            <HistorySeed :history="internalHistory" />

            <div class="flex-1 flex justify-end">
                <CreatePeriodModal>
                    <button
                        class="px-4 py-2 bg-indigo-700 rounded text-white font-bold"
                        @click="() => create(lastPosition + 1)"
                    >Add Period</button>
                </CreatePeriodModal>
            </div>
        </div>

        <div class="flex-grow relative">
            <draggable
                :list="orderedPeriods"
                @change="onPeriodMoved"
                handle=".handle"
                class="absolute inset-0 overflow-x-auto overflow-y-hidden whitespace-nowrap pb-4 px-3 space-x-2"
                item-key="id"
            >
                <template #item="{element}">
                    <div class="w-72 inline-block px-1 h-full align-top">
                        <PeriodCard
                            :period="element"
                            :history-id="history.id"
                            @insertPeriod="create"
                        />
                    </div>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import sortBy from 'lodash/sortBy';
import each from 'lodash/each';
import draggable from 'vuedraggable';
import { Link } from "@inertiajs/inertia-vue3";

import { useEmitter } from "../composables/useEmitter";
import PlayerList from "./PlayerList.vue";
import PeriodCard from "./PeriodCard.vue";
import FocusTracker from "./FocusTracker.vue";
import Palette from "./Palette.vue";
import LegacyTracker from "./LegacyTracker.vue";
import Modal from "./Modal.vue";
import GamePanel from "./GamePanel.vue";
import HistorySeed from "./HistorySeed.vue";
import CreatePeriodModal from "./Modal/CreatePeriodModal";

export default {
    name: 'GameBoard',

    props: {
        history: {
            type: Object,
            required: true,
        },
    },

    components: {
        CreatePeriodModal,
        HistorySeed,
        GamePanel,
        Modal,
        LegacyTracker,
        draggable,
        PeriodCard,
        PlayerList,
        FocusTracker,
        Palette,
        Link,
    },

    data() {
        return {
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

    created() {
        this.emitter.on('event.moved', this.onEventMoved);
        this.emitter.on('scene.moved', this.onSceneMoved);

        Echo.join(this.channelName)
            .listen('BoardUpdated', this.resyncBoard)
            .listen('HistorySeedUpdated', this.updateSeed);
    },

    beforeUnmount() {
        this.emitter.off('event.moved', this.onEventMoved)
        this.emitter.off('scene.moved', this.onSceneMoved);
        Echo.leave(this.channelName);
    },

    setup() {
        return { emitter: useEmitter() };
    }
};
</script>
