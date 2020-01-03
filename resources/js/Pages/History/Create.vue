<template>
    <div class="container mx-auto pt-12 px-4">
        <section class="p-4 rounded shadow w-full md:w-1/2 bg-white mx-auto border-2 border-indigo-700">
            <h1 class="text-xl font-bold mb-4">Create New History</h1>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="label" for="name">Name</label>
                    <input type="text" class="input" id="name" v-model="form.name">
                    <small v-if="$page.errors.name" class="text-xs text-red-500">{{ $page.errors.name[0] }}</small>
                </div>

                <button class="bg-indigo-600 py-3 text-white rounded font-bold test-sm w-full">
                    Create
                </button>
            </form>
        </section>
    </div>
</template>

<script>
import Layout from '../Layouts/Layout';

export default {
    name: 'Create',

    layout: Layout,

    data() {
        return {
            form: {
                name: null,
            },
        };
    },

    methods: {
        submit() {
            this.$inertia.post(this.$route('history.store'), this.form);
        },
    },
};
</script>
