<template>
    <div class="pb-6 pt-4 relative game-card">
        <button
            title="Add event above this event"
            @click="$emit('insertEvent', event.position)"
            class="game-add-button top flex items-center text-sm font-bold text-gray-500 hover:text-indigo-700"
        >
            <Icon name="add-solid" class="fill-current w-6 mr-2" />
            Event
        </button>

        <button
            title="Add event below this event"
            @click="$emit('insertEvent', event.position + 1)"
            class="game-add-button bottom flex items-center text-sm font-bold text-gray-500 hover:text-indigo-700"
        >
            <Icon name="add-solid" class="fill-current w-6 mr-2" />
            Event
        </button>

        <article class="relative p-8 relative shadow-lg rounded-lg border bg-white border-gray-200 text-sm w-full min-h-32 group panzoom-exclude">
            <template v-if="!editing">
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

                <h4 class="text-center">{{ event.name }}</h4>

                <div class="absolute invisible group-hover:visible flex justify-end items-center inset-x-0 bottom-0 px-2 pb-2">
                    <button class="text-sm text-indigo-700" @click="createScene">Create Scene</button>
                </div>

                <p
                    class="absolute top-0 text-sm bg-white text-gray-700 font-bold leading-loose uppercase px-1"
                    style="top: -15px; right: 20px;"
                >
                    Event
                </p>

                <div
                    class="rounded-full border-2 border-gray-800 h-6 w-6 absolute"
                    style="top: -12px"
                    :class="{ 'bg-white': event.type === 'light', 'bg-gray-800': event.type === 'dark' }"
                ></div>
            </template>

            <form v-else @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="label">Name</label>
                    <textarea id="name" rows="4" class="input" v-model="form.name"></textarea>
                </div>

                <div class="mb-4">
                    <p class="label">Tone</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <input type="radio" id="dark" value="dark" v-model="form.type">
                            <label for="dark">Dark</label>
                        </div>

                        <div>
                            <input type="radio" id="light" value="light" v-model="form.type">
                            <label for="light">Light</label>
                        </div>
                    </div>
                </div>


                <LoadingButton :loading="loading">
                    {{ loading ? 'Hang on...' : 'Save' }}
                </LoadingButton>

                <button type="button" class="w-full text-gray-700 mt-2" @click="cancel">Cancel</button>
            </form>
        </article>

        <draggable :list="orderedScenes" @change="sceneMoved" handle=".handle">
            <SceneCard v-for="scene in orderedScenes" :scene="scene" :key="scene.id" />
        </draggable>

        <SceneModal v-if="showSceneModal" :event="event" @close="closeSceneModal" />
    </div>
</template>

<script>
import axios from 'axios';
import sortBy from 'lodash/sortBy';
import draggable from 'vuedraggable';

import LoadingButton from "./LoadingButton.vue";
import SettingsPanel from "./SettingsPanel.vue";
import SceneCard from "./SceneCard.vue";
import SceneModal from "./Modal/SceneModal.vue";
import Icon from "./Icon.vue";

export default {
    name: 'EventCard',

    props: ['event', 'period'],

    inject: ['history'],

    components: {
        Icon,
        SceneModal,
        draggable,
        SceneCard,
        SettingsPanel,
        LoadingButton,
    },

    computed: {
        orderedScenes() {
            return sortBy(this.event.scenes, ['position']);
        },
    },

    data() {
        return {
            editing: false,
            loading: false,
            showSceneModal: false,
            form: {
                type: this.event.type,
                name: this.event.name,
            },
        };
    },

    methods: {
        sceneMoved(e) {
            if (!e.moved) {
                return;
            }

            Bus.$emit('scene.moved', {
                period: this.period,
                event: this.event,
                scene: e.moved.element,
                position: e.moved.newIndex + 1,
            });
        },

        submit()  {
            if (
                this.form.type === this.event.type
                && this.form.name === this.event.name
            ) {
                this.editing = false;
                return;
            }

            this.loading = true;

            axios.put(this.$route('events.update', [this.history, this.event]), this.form)
                .then(() => {
                    this.loading = false;
                    this.editing = false
                })
                .catch((err) => {
                    this.loading = false;
                    console.error(err);
                });
        },

        remove() {
            const confirmed = confirm('Really delete this event? All scenes belonging to this event will be deleted too!');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('events.delete', [this.history, this.event]))
                .catch(console.error);
        },

        edit() {
            this.form.type = this.event.type;
            this.form.name = this.event.name;
            this.editing = true;
        },

        cancel() {
            this.form.type = this.event.type;
            this.form.name= this.event.name;
            this.editing = false;
        },

        createScene() {
            this.showSceneModal = true;
        },

        closeSceneModal() {
            this.showSceneModal = false;
            this.selectedScene = null;
        },
    },
};
</script>
