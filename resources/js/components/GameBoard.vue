<template>
    <div class="flex flex-col justify-between flex-1 py-4">
        <draggable :list="orderedPeriods" @change="onPeriodMoved" class="px-4 flex -mx-4">
            <PeriodCard v-for="period in orderedPeriods" :period="period" :key="period.id" />
        </draggable>

        <div class="border-t border-gray-300">
            <div class="px-4 pt-4 flex justify-between">
                <FocusTracker :channel="'history.' + history.id" :foci="history.focus" />

                <PlayerList :channel="'history.' + history.id" />
            </div>
        </div>
    </div>
</template>

<script>
import sortBy from 'lodash/sortBy';
import each from 'lodash/each';
import find from 'lodash/find';
import draggable from 'vuedraggable';

import PlayerList from './PlayerList';
import PeriodCard from './PeriodCard';
import FocusTracker from './FocusTracker';

export default {
    name: 'GameBoard',

    props: {
        history: {
            type: Object,
            required: true,
        },
    },

    components: {
        draggable,
        PeriodCard,
        PlayerList,
        FocusTracker,
    },

    data() {
        return {
            periods: this.history.periods,
        };
    },

    computed: {
        orderedPeriods() {
            return sortBy(this.periods, ['position']);
        }
    },

    methods: {
        addPeriod({ period }) {
            this.periods.push(Object.assign({}, period, { events: [] }));
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

        updatePeriod({ period }) {
            const match = find(this.periods, p => p.id === period.id);

            Object.assign(match, period);
        },

        deletePeriod({ id }) {
            this.periods = this.periods.filter(p => p.id !== id);
        },

        addEvent({ event, period }) {
            const match = find(this.periods, p => p.id === period);

            match.events.push(event);
        },

        updateEvent({ period, event }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            const matchingEvent = matchingPeriod.events.find(e => e.id === event.id);

            if (!matchingEvent) {
                return;
            }

            Object.assign(matchingEvent, event);
        },

        deleteEvent({ id, period }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            matchingPeriod.events = matchingPeriod.events.filter(e => e.id !== id);
        },

        onPeriodMoved(e) {
            if (!e.moved) {
                return;
            }

            const { element, newIndex } = e.moved;

            this.updatePeriodPositions({ id: element.id, position: newIndex + 1 });

            axios.post(`/histories/${this.history.id}/periods/${element.id}/move`, {
                position: newIndex + 1,
            }).catch(console.error);
        },

        addScene({ scene, event, period }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            const matchingEvent = matchingPeriod.events.find(e => e.id === event);

            if (!matchingEvent) {
                return;
            }

            matchingEvent.scenes.push(scene);
        },

        updateScene({ scene, event, period }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            const matchingEvent = matchingPeriod.events.find(e => e.id === event);

            if (!matchingEvent) {
                return;
            }

            const matchingScene = matchingEvent.scenes.find(s => s.id === scene.id);

            if (!matchingScene) {
                return;
            }

            Object.assign(matchingScene, scene);
        },

        deleteScene({ id, event, period }) {
            const matchingPeriod = this.periods.find(p => p.id === period);

            if (!matchingPeriod) {
                return;
            }

            const matchingEvent = matchingPeriod.events.find(e => e.id === event);

            if (!matchingEvent) {
                return;
            }

            matchingEvent.scenes = matchingEvent.scenes.filter(s => s.id !== id);
        },

        onPeriodSaved({ period, payload }) {
            this.updatePeriod({
                period: Object.assign({}, payload, {id: period})
            });

            axios.put(this.$route('periods.update', period), payload)
                .catch(console.error);
        },

        onPeriodRemoved(payload) {
            this.deletePeriod(payload);

            axios.delete(this.$route('periods.delete', payload))
                .catch(console.error);
        },

        onEventMoved({ period, event, position }) {
            this.updateEventPositions({ id: event.id, position, period: period.id});

            axios.post(this.$route('events.move', event), {
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

            axios.post(`/histories/${this.history.id}/periods/${period.id}/events/${event.id}/scenes/${scene.id}/move`, {
                position: position,
            }).catch(console.error);
        },

        onEventSaved({ period, event, payload }) {
            this.updateEvent({
                period,
                event: Object.assign({}, payload, { id: event }),
            });

            axios.put(this.$route('history.periods.events.update', [this.history, period, event]), payload)
                .catch(console.error);
        },

        onEventRemoved({ period, event }) {
            this.deleteEvent({ id: event, period });

            axios.delete(this.$route('events.delete', event))
                .catch(console.error);
        }
    },

    created() {
        Bus.$on('period.saved', this.onPeriodSaved);
        Bus.$on('period.removed', this.onPeriodRemoved);
        Bus.$on('event.moved', this.onEventMoved);
        Bus.$on('scene.moved', this.onSceneMoved);
        Bus.$on('event.saved', this.onEventSaved);
        Bus.$on('event.removed', this.onEventRemoved);

        Echo.join(`history.${this.history.id}`)
            .listen('PeriodCreated', this.addPeriod)
            .listen('PeriodUpdated', this.updatePeriod)
            .listen('PeriodMoved', this.updatePeriodPositions)
            .listen('PeriodDeleted', this.deletePeriod)
            .listen('EventCreated', this.addEvent)
            .listen('EventUpdated', this.updateEvent)
            .listen('EventDeleted', this.deleteEvent)
            .listen('EventMoved', this.updateEventPositions)
            .listen('SceneCreated', this.addScene)
            .listen('SceneUpdated', this.updateScene)
            .listen('SceneDeleted', this.deleteScene)
            .listen('SceneMoved', this.updateScenePositions);
    },

    beforeDestroy() {
        Bus.$off([
            'period.saved',
            'period.removed',
            'event.moved',
            'event.saved',
            'event.removed',
            'scene.moved',
        ]);
        Echo.leave('history');
    },
};
</script>
