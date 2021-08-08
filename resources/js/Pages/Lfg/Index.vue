<template>
    <div class="mx-auto flex">
        <div class="w-64 bg-gray-100">
            <h2 class="text-lg text-black font-semibold pt-8 px-4">Filter</h2>
        </div>

        <div class="pl-12 pt-8">
            <h1 class="text-xl font-bold">Upcoming games</h1>
            <p class="text-gray-700 mt-4">
                Check out who is looking for more people to play with. Can’t find anything? No problem! Why not
                <Link :href="$route('lfg.create')" class="text-indigo-700 font-semibold">create a game yourself</Link>?
            </p>

            <LfgList :games="games" class="mt-8" />
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import Layout from "@/Pages/Layouts/Layout.vue";
import LfgList from "@/components/LfgList.vue";

export default {
    name: "Index",

    components: {
        LfgList,
        Link,
    },

    layout: Layout,

    computed: {
        games() {
            return this.$page.props.games;
        }
    },

    data() {
        return {
            filter: {
                start_date: this.$route.query ? this.$route.query.start_date : null,
            }
        };
    }
}
</script>
