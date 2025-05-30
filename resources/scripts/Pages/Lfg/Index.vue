<template>
    <div class="mx-auto max-w-7xl flex">
        <div class="w-64 bg-gray-100">
            <h2 class="text-lg text-black font-semibold pt-8 px-4">Filter</h2>
        </div>

        <div class="pl-12 pt-8">
            <h1 class="text-xl font-bold">Upcoming games</h1>
            <p class="text-gray-700 mt-4">
                Check out who is looking for more people to play with. Can’t find anything? No problem! Why not
                <Link :href="route('lfg.create')" class="text-indigo-700 font-semibold">create a game yourself</Link>?
            </p>

            <LfgList :games="page.props.games" class="mt-8" />
        </div>
    </div>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Layout.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { reactive } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

import { Lfg } from "@/types";
import LfgList from "@/components/LfgList.vue";

const page = usePage<{ games: Array<Lfg> }>();
const filter = reactive({
    start_date: route.query ? route.query.start_date : null,
});
</script>
