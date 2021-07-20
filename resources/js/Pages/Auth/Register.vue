<template>
    <div class="container mx-auto pt-8 px-4">
        <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
            <form @submit.prevent="submit" method="POST">
                <div class="mb-4">
                    <label class="label" for="name">Name</label>
                    <input v-model="form.name" class="input" type="text" id="name" name="name" autofocus required>
                    <small class="text-xs text-gray-600 mt-1">Your name will show up to other players when playing a game. You can use a nickname if you want.</small>

                    <small v-if="form.errors.name" class="mt-1 text-xs text-red-400 block">
                        {{ form.errors.name[0] }}
                    </small>
                </div>

                <div class="mb-4">
                    <label class="label" for="email">Email</label>
                    <input v-model="form.email" class="input" type="email" id="email" name="email" required>
                    <small class="text-xs text-gray-600 mt-1">
                        Your email is used to create the account and, if necessary, send you a password reset link. That's it. It will not be shared with anyone or used to send you newsletters or other marketing bullshit.
                    </small>

                    <small v-if="form.errors.email" class="mt-1 text-xs text-red-400 block">{{ form.errors.email[0] }}</small>
                </div>

                <div class="mb-4">
                    <label class="label" for="password">Password</label>
                    <input v-model="form.password" class="input" type="password" id="password" name="password" required>

                    <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">
                        {{ form.errors.password[0] }}
                    </small>
                </div>

                <div class="mb-4">
                    <label class="label" for="password_confirmation">Confirm Password</label>
                    <input v-model="form.password_confirmation" class="input" type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Register</button>

                <InertiaLink :href="$route('login')" class="mt-2 text-sm text-indigo-700 text-center block">
                    Already have an account? Log in instead
                </InertiaLink>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout.vue";

export default {
    name: "Register",

    layout: Layout,

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }),
        }
    },

    methods: {
        submit() {
            this.form.post(this.$route('register'));
        }
    }
}
</script>
