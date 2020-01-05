<template>
    <div class="flex items-center relative">
        <Gravatar
            :email="$page.auth.user.email"
            :size="30"
            class="mr-2 rounded-full border-2 border-indigo-500"
        />

        <div class="relative py-6">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center">
                <span class="text-indigo-400">Hello,&nbsp;</span>
                <span class="font-bold text-indigo-100 mr-1">{{ $page.auth.user.name }}</span>
                <Icon name="chevron-down" class="h-4 w-4 fill-current text-gray-200" />
            </button>
        </div>

        <div
            v-if="dropdownOpen"
            v-click-outside="() => dropdownOpen = false"
            class="absolute bg-white border border-gray-300 shadow-lg right-0 rounded"
            style="top: 100%"
        >
            <InertiaLink
                :href="$route('profile')"
                class="px-8 py-2 text-sm text-gray-800 block hover:bg-gray-200 inline-flex items-center"
                @click="dropdownOpen = false"
            >
                <Icon name="user" class="fill-current h-3 w-3 text-gray-600 mr-2" />
                Profile
            </InertiaLink>

            <form :action="$route('logout')" method="POST" class="hover:bg-gray-200 border-t border-gray-300">
                <input type="hidden" name="_token" :value="token">
                <button class="text-sm text-indigo-600 w-full px-8 py-2">Logout</button>
            </form>
        </div>
    </div>
</template>

<script>
import Gravatar from 'vue-gravatar';
import vClickOutside from 'v-click-outside';
import Icon from "./Icon";

export default {
    name: 'UserNavigation',

    components: {
        Icon,
        Gravatar,
    },

    directives: {
        clickOutside: vClickOutside.directive,
    },

    computed: {
        token() {
            return document.head.querySelector('meta[name=csrf-token]').getAttribute('content');
        }
    },

    data() {
        return {
            dropdownOpen: false,
        };
    },
};
</script>
