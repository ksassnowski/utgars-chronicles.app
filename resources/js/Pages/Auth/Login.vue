<template>
    <div class="container mx-auto pt-8 px-4">
        <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="label" for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" v-model="form.email" required autofocus>
                    <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email[0] }}</small>
                </div>

                <div class="mb-4">
                    <label class="label" for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" v-model="form.password" required>
                    <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">
                        {{ form.errors.password[0] }}
                    </small>

                    <InertiaLink :href="$route('password.request')" class="mt-1 text-sm text-gray-600 block">
                        Forgot password?
                    </InertiaLink>
                </div>

                <div class="mb-4 space-x-1">
                    <input v-model="form.remember" type="checkbox" name="remember" id="remember">
                    <label for="remember" class="text-sm text-gray-700">Stay logged in</label>
                </div>

                <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Login</button>

                <InertiaLink :href="$route('register')" class="mt-2 text-sm text-indigo-700 text-center block">
                    Or create a new account
                </InertiaLink>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout.vue";

export default {
    name: "Login",

    layout: Layout,

    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false,
            }),
        }
    },

    methods: {
        submit() {
            this.form.post(this.$route('login'));
        }
    }
}
</script>
