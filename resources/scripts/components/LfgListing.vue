<template>
    <div class="space-y-1 p-4 rounded-lg bg-gray-100">
        <h3 class="font-bold text-indigo-700">{{ game.title }}</h3>

        <p class="flex items-center space-x-2 text-gray-800">
            <CalendarIcon class="w-4 h-4 text-gray-600" />
            <span>{{ date }}</span>
        </p>

        <p class="flex items-center space-x-2 text-gray-700 text-sm">
            <UserGroupIcon class="w-4 h-4 text-gray-500" />
            <span
                >{{ game.users_count + 1 }} /
                <span class="font-bold">{{ game.slots }}</span></span
            >
        </p>
    </div>
</template>

<script lang="ts" setup>
import { CalendarIcon, UserGroupIcon } from "@heroicons/vue/outline";
import dayjs from "dayjs";

import { Lfg } from "@/types";

const props = defineProps<{ game: Lfg }>();

const date = (() => dayjs
    .utc(props.game.start_date)
    .local()
    .format("D. MMM YYYY hh:mm a"))();
</script>
