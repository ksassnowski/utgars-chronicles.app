<template>
    <div class="container mx-auto pt-8 px-4">
        <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
            <form @submit.prevent="submit" method="POST">
                <div class="mb-4">
                    <label class="label" for="email">Email</label>
                    <input v-model="form.email" class="input" type="email" id="email" name="email" required>

                    <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">
                        {{ form.errors.email[0] }}
                    </small>
                </div>

                <div class="mb-4">
                    <label class="label" for="password">New Password</label>
                    <input v-model="form.password" class="input" type="password" name="password" id="password" autofocus required>

                    <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">{{ form.errors.password[0] }}</small>
                </div>

                <div class="mb-4">
                    <label class="label" for="password_confirmation">Confirm Password</label>
                    <input v-model="form.password_confirmation" class="input" type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Change Password</button>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout.vue";

export default {
    name: "Reset",

    layout: Layout,

    data() {
        return {
            form: this.$inertia.form({
                email: this.$page.props.email,
                token: this.$page.props.token,
                password: '',
                password_confirmation: '',
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.$route('password.update'));
        }
    }
}
</script>
