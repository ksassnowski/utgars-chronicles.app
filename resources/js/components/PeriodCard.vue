<template>
    <div class="w-1/2 md:w-1/4 lg:w-1/5 flex-shrink-0">
        <CreateEventModal
            v-if="showModal"
            :period="period"
            title="Create Event"
            @close="showModal = false"
        />

        <article class="border-2 border-gray-600 p-8 rounded-sm mb-6 shadow bg-white relative mx-4 relative group panzoom-exclude">
            <template v-if="!editing">
                <div class="invisible group-hover:visible absolute right-0 top-0 pr-2 pt-2 flex justify-end z-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="handle w-4 h-4 fill-current text-gray-600 cursor-move" style="margin-top: 2px" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>

                    <SettingsPanel
                        v-if="!editing"
                        @delete="remove"
                        @edit="edit"
                    />
                </div>

                <h3 class="font-bold tracking-wide text-center">{{ period.name }}</h3>

                <div
                    class="rounded-full w-6 h-6 border-2 border-gray-800 absolute"
                    style="top: -12px;"
                    :class="{ 'bg-white': period.type === 'light', 'bg-gray-800': period.type === 'dark' }"
                ></div>

                <p
                    class="absolute top-0 text-sm bg-white text-gray-700 font-bold leading-loose uppercase px-1"
                    style="top: -15px; right: 20px;"
                >
                    Period
                </p>

                <div class="flex justify-end absolute inset-x-0 bottom-0 p-2 invisible group-hover:visible">
                    <button class="text-indigo-700 text-sm" @click="showModal = true">Add Event</button>
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

                <LoadingButton :loading="loading">
                    {{ loading ? 'Hang on...' : 'Save' }}
                </LoadingButton>

                <button type="button" class="text-sm text-gray-700 mt-2 text-center w-full" @click="cancel">Cancel</button>
            </form>
        </article>

        <draggable :list="orderedEvents" @change="eventMoved" class="flex flex-col px-4" handle=".handle">
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
import axios from 'axios';
import draggable from 'vuedraggable';
import sortBy from 'lodash/sortBy';

import EventCard from './EventCard';
import SettingsPanel from './SettingsPanel';
import Modal from './Modal';
import CreateEventModal from './Modal/CreateEventModal';
import LoadingButton from "./LoadingButton";
import Icon from "./Icon";

export default {
    name: 'PeriodCard',

    props: ['period', 'historyId'],

    components: {
        Icon,
        LoadingButton,
        CreateEventModal,
        Modal,
        SettingsPanel,
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
            loading: false,
            showModal: false,
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

        edit() {
            this.editing = true;
            this.form.name = this.period.name;
            this.form.type = this.period.type;
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

            this.loading = true;

            axios.put(this.$route('periods.update', this.period), this.form)
                .then(() => {
                    this.loading = false;
                    this.editing = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        remove() {
            const confirmed = confirm('Really delete this period? This will delete all events and scenes as well!');

            if (confirmed) {
                axios.delete(this.$route('periods.delete', this.period))
                    .catch(console.error);
            }
        },
    },
};
</script>
