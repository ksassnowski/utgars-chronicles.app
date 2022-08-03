<template>

    <div class="container">
        <div class="container mx-auto pt-8 px-4">
            <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
                <form @submit.prevent="submit" method="POST">
                    <div class="mb-4">
                        <label class="label" for="email">Email</label>
                        <input v-model="form.email" class="input" type="email" name="email" id="email" required autofocus>

                        <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">
                            {{ form.errors.email[0] }}
                        </small>
                    </div>

                    <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">
                        Send Password Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout.vue";

export default {
    name: "Email",

    layout: Layout,

    data() {
        return {
            form: this.$inertia.form({
                email: '',
            })
        };
    },

    methods: {
        submit() {
            this.form.post(this.$route('password.email'));
        }
    }
}
</script>
