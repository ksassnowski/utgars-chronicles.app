<template>
    <div class="w-1/2 md:w-1/4 lg:w-1/5">
        <article class="border-2 border-gray-600 p-8 rounded-sm mb-6 shadow bg-white relative mx-4 relative group">
            <template v-if="!editing">
                <h3 class="font-bold tracking-wide text-center">{{ period.name }}</h3>

                <div
                    class="rounded-full w-6 h-6 border-2 border-gray-800 absolute"
                    style="top: -12px;"
                    :class="{ 'bg-white': period.type === 'light', 'bg-gray-800': period.type === 'dark' }"
                ></div>

                <div class="absolute inset-x-0 bottom-0 flex justify-between items-center px-2 pb-2 invisible group-hover:visible">
                    <button
                        class="text-sm text-red-500"
                        @click="remove"
                    >
                        Delete
                    </button>

                    <button
                        class="text-sm text-indigo-700"
                        @click="editing = true"
                    >Edit</button>
                </div>
            </template>

            <form v-else @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="label">Name</label>
                    <input type="text" id="name" v-model="form.name" class="input">
                </div>

                <div class="mb-4">
                    <p class="label">Tone</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <label for="dark" class="label">Dark</label>
                            <input type="radio" id="dark" value="dark" v-model="form.type">
                        </div>

                        <div>
                            <label for="light" class="label">Light</label>
                            <input type="radio" id="light" value="light" v-model="form.type">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" class="text-sm text-gray-500" @click="cancel">Cancel</button>
                    <button type="submit" class="text-sm text-indigo-600">Save</button>
                </div>
            </form>
        </article>

        <draggable :list="orderedEvents" @change="eventMoved" class="flex flex-col px-4">
            <EventCard
                v-for="event in orderedEvents"
                :key="event.id"
                :event="event"
                :period="period"
            />
        </draggable>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import sortBy from 'lodash/sortBy';

import EventCard from './EventCard';

export default {
    name: 'PeriodCard',

    props: ['period'],

    components: {
        draggable,
        EventCard,
    },

    computed: {
        orderedEvents() {
            return sortBy(this.period.events, ['position']);
        }
    },

    data() {
        return {
            editing: false,
            form: {
                name: this.period.name,
                type: this.period.type,
            },
        };
    },

    methods: {
        eventMoved(e) {
            if (!e.moved) {
                return;
            }

            Bus.$emit('event.moved', {
                period: this.period,
                event: e.moved.element,
                position: e.moved.newIndex + 1,
            })
        },

        cancel() {
            this.editing = false;
            this.form.name = this.period.name;
            this.form.type = this.period.type;
        },

        submit() {
            if (
                this.form.name === this.period.name
                && this.form.type === this.period.type
            ) {
                this.editing = false;
                return;
            }

            Bus.$emit('period.saved', {
                period: this.period.id,
                payload: this.form,
            });

            this.editing = false;
        },

        remove() {
            Bus.$emit('period.removed', {
                id: this.period.id
            });
        },
    },
};
</script>
