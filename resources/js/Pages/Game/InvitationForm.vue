<template>
    <div class="flex justify-center items-center h-full game-container px-4">
        <form
            @submit.prevent="submit"
            class="w-full sm:w-2/3 lg:w-2/5 bg-white rounded border border-gray-200 p-6 shadow-xl"
        >
            <p class="text-2xl mb-4 text-gray-800 text-center">
                <strong>{{ inviteeName }}</strong> has invited you to join their
                game of Microscope.
            </p>

            <div class="mb-4">
                <label for="name" class="label">Name</label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    v-model="form.name"
                    autofocus
                    required
                />
                <small class="text-xs text-gray-600"
                    >This is the name that will show up to the other players
                    in-game.</small
                >
            </div>

            <div class="flex justify-end">
                <button
                    class="bg-indigo-700 text-white font-bold px-6 py-3 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!formValid"
                >
                    Join Game
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "InvitationForm",

    props: {
        acceptUrl: {
            type: String,
            required: true
        },
        inviteeName: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            form: {
                name: null
            }
        };
    },

    computed: {
        formValid() {
            return this.form;
        }
    },

    methods: {
        submit() {
            if (this.formValid) {
                this.$inertia.post(this.acceptUrl, this.form);
            }
        }
    }
};
</script>
