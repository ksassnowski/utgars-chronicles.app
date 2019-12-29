<template>
    <div class="w-1/3 mx-auto bg-white p-4 shadow-lg rounded">
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="email">Email</label>
                <input class="input" type="email" v-model="form.email" id="email">
                <small v-if="$page.errors.email" class="mt-1 text-xs text-red-400">{{ $page.errors.email[0] }}</small>
            </div>

            <div class="mb-4">
                <label for="password">Password</label>
                <input class="input" type="password" v-model="form.password" id="password">
                <small v-if="$page.errors.password" class="mt-1 text-xs text-red-400">{{ $page.errors.password[0] }}</small>
            </div>

            <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Login</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'Login',

    data() {
        return {
            form:  {
                email: null,
                password: null,
            },
        };
    },

    methods: {
        submit()  {
            this.$inertia.post('/login', this.form);
        }
    }
};
</script>
