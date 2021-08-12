import Echo from 'laravel-echo';
import Pusher from "pusher-js";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import Timezone from 'dayjs/plugin/timezone';
import RelativeTime from 'dayjs/plugin/relativeTime';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});

dayjs.extend(utc);
dayjs.extend(Timezone);
dayjs.extend(RelativeTime);
