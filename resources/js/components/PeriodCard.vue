<template>
    <div class="w-1/2 md:w-1/4 lg:w-1/5 flex-shrink-0">
        <CreateEventModal
            v-if="showModal"
            :period="period"
            title="Create Event"
            @close="showModal = false"
        />

        <div class="px-4 group">
            <article class="p-8 rounded-lg border border-gray-200 mb-6 shadow-lg bg-white relative panzoom-exclude">
                <template v-if="!editing">
                    <button
                        title="Add period before this one"
                        class="game-add-button left invisible group-hover:visible"
                        @click="$emit('insertPeriod', period.position)"
                    >
                        <Icon name="add-solid" class="fill-current text-gray-500 hover:text-indigo-700 w-6" />
                    </button>

                    <button
                        title="Add period after this one"
                        class="game-add-button right invisible group-hover:visible"
                        @click="$emit('insertPeriod', period.position + 1)"
                    >
                        <Icon name="add-solid" class="fill-current text-gray-500 hover:text-indigo-700 w-6" />
                    </button>

                    <div class="invisible group-hover:visible absolute left-0 top-0 w-full pl-3 pr-2 pt-2 flex justify-between z-20">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="handle w-4 h-4 fill-current text-gray-400 cursor-move"
                            style="margin-top: 2px"
                            viewBox="0 0 20 20"
                        ><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>

                        <SettingsPanel
                            v-if="!editing"
                            @delete="remove"
                            @edit="edit"
                        />
                    </div>

                    <h3 class="font-bold tracking-wide text-center py-2">{{ period.name }}</h3>

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
                        <textarea type="text" id="name" v-model="form.name" class="input"></textarea>
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
        </div>

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
