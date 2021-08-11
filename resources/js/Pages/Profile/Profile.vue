<template>
    <div class="container mx-auto px-4 pt-8">
        <div class="lg:w-3/5 mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-2 tracking-tight">
                Profile
            </h1>

            <div class="rounded shadow-lg py-6 border border-gray-300 px-4">
                <form @submit.prevent="changePassword" class="flex">
                    <div class="w-1/3 pr-4">
                        <p class="text-lg text-gray-800">Change Password</p>
                    </div>

                    <div class="w-2/3">
                        <div
                            class="mb-4"
                            :class="{ error: $page.props.errors.password }"
                        >
                            <label for="password" class="label"
                                >New Password</label
                            >
                            <input
                                type="password"
                                class="input"
                                id="password"
                                v-model="password.form.password"
                            />
                            <small
                                v-if="$page.props.errors.password"
                                class="text-xs text-red-500 mt-1"
                                >{{ $page.props.errors.password[0] }}</small
                            >
                        </div>

                        <div class="mb-4">
                            <label for="passwordConfirmation" class="label"
                                >Confirm Password</label
                            >
                            <input
                                type="password"
                                class="input"
                                id="passwordConfirmation"
                                v-model="password.form.password_confirmation"
                            />
                        </div>

                        <LoadingButton
                            class="px-8 py-2 bg-indigo-700 rounded text-white"
                            :loading="password.loading"
                        >
                            Change Password
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "../Layouts/Layout.vue";
import LoadingButton from "../../components/LoadingButton.vue";

export default {
    name: "Profile",

    metaInfo() {
        return {
            title: "Profile – Utgar's Chronicles",
        };
    },

    components: {
        LoadingButton,
    },

    layout: Layout,

    data() {
        return {
            password: {
                loading: false,
                form: {
                    password: null,
                    password_confirmation: null,
                },
            },
        };
    },

    methods: {
        changePassword() {
            this.password.loading = true;

            this.$inertia
                .post(this.$route("password.change"), this.password.form)
                .then(() => {
                    this.password.form = {
                        password: null,
                        password_confirmation: null,
                    };
                })
                .finally(() => {
                    this.password.loading = false;
                });
        },
    },
};
</script>
