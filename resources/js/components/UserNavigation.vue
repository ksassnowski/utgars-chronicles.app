<template>
    <div class="w-full items-center flex-grow sm:flex sm:w-auto">
        <div class="flex items-center flex-grow">
            <InertiaLink :href="$route('home')" class="text-indigo-100 py-4 sm:py-6 flex items-center hover:bg-indigo-700 sm:px-4">
                Dashboard
            </InertiaLink>
        </div>

        <div class="sm:flex items-center relative">
            <ul class="sm:mr-4">
                <li class="sm:px-4 hover:bg-indigo-700">
                    <FeedbackModal />
                </li>
            </ul>

            <div class="flex items-center">
                <Gravatar
                    :email="$page.auth.user.email"
                    :size="30"
                    class="mr-2 rounded-full border-2 border-indigo-500"
                />

                <div class="relative py-4 sm:py-6">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center">
                        <span class="text-indigo-400">Hello,&nbsp;</span>
                        <span class="font-bold text-indigo-100 mr-1">{{ $page.auth.user.name }}</span>
                        <Icon name="chevron-down" class="h-4 w-4 fill-current text-gray-200" />
                    </button>
                </div>
            </div>

            <div
                v-if="dropdownOpen"
                v-click-outside="() => dropdownOpen = false"
                class="border-t border-indigo-100 sm:absolute sm:bg-white sm:border sm:border-gray-300 sm:shadow-lg sm:right-0 sm:rounded sm:flex sm:flex-col sm:z-10"
                style="top: 100%"
            >
                <InertiaLink
                    :href="$route('profile')"
                    class="sm:px-8 py-4 sm:py-2 sm:text-sm text-indigo-100 sm:text-gray-800 block hover:bg-gray-200 inline-flex items-center"
                    @click="dropdownOpen = false"
                >
                    <Icon name="user" class="fill-current h-4 w-4 sm:h-3 sm:w-3 text-indigo-300 sm:text-gray-600 mr-2" />
                    Profile
                </InertiaLink>

                <form :action="$route('logout')" method="POST" class="hover:bg-gray-200">
                    <input type="hidden" name="_token" :value="token">
                    <button class="sm:text-sm text-indigo-100 sm:text-indigo-600 w-full sm:px-8 py-4 sm:py-2 text-left">Logout</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Gravatar from 'vue-gravatar';
import vClickOutside from 'v-click-outside';

import Icon from "./Icon";
import FeedbackModal from "./Modal/FeedbackModal";

export default {
    name: 'UserNavigation',

    components: {
        FeedbackModal,
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
