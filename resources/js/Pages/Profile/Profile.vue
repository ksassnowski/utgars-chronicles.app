<template>
    <div class="container mx-auto px-4">
        <div class="lg:w-3/5 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-2 tracking-tight">Profile</h1>

            <div class="rounded shadow-lg py-6 border border-gray-300 px-4">
                <form @submit.prevent="changePassword" class="flex">
                    <div class="w-1/3 pr-4">
                        <p class="text-lg text-gray-800">Change Password</p>
                    </div>

                    <div class="w-2/3">
                        <div class="mb-4">
                            <label for="password" class="label">New Password</label>
                            <input type="password" class="input" id="password" v-model="passwordForm.password">
                        </div>

                        <div class="mb-4">
                            <label for="passwordConfirmation" class="label">Confirm Password</label>
                            <input type="password" class="input" id="passwordConfirmation" v-model="passwordForm.password_confirmation">
                        </div>

                        <button class="px-8 py-2 bg-indigo-700 rounded text-white">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout";

export default {
    name: 'Profile',

    layout: Layout,

    data() {
        return {
            passwordForm: {
                password: null,
                password_confirmation: null,
            },
        };
    },

    methods: {
        changePassword() {
            this.$inertia.post(this.$route('password.change'), this.passwordForm);
        },
    },
};
</script>
