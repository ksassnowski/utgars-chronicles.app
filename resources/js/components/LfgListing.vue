<template>
    <div class="space-y-1 p-4 rounded-lg bg-gray-100">
        <h3 class="font-bold text-indigo-700">{{ game.title }}</h3>

        <p class="flex items-center space-x-2 text-gray-800">
            <Icon name="calendar" class="w-4 h-4 text-gray-600 fill-current" />
            <span>{{ date }}</span>
        </p>

        <p class="flex items-center space-x-2 text-gray-700 text-sm">
            <Icon name="user-group" class="w-3 h-3 text-gray-500 fill-current" />
            <span>{{ game.users_count + 1 }} / <span class="font-bold">{{ game.slots }}</span></span>
        </p>
    </div>
</template>

<script>
import dayjs from "dayjs";
import Icon from "@/components/Icon.vue";

export default {
    name: "LfgListing",

    components: {
        Icon
    },

    props: {
        game: {
            type: Object,
            required: true,
        },
    },

    computed: {
        date() {
            return dayjs.utc(this.game.start_date).local().format('D. MMM YYYY hh:mm a');
        }
    }
}
</script>
