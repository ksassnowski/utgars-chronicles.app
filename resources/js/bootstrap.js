import Echo from 'laravel-echo';
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import Timezone from 'dayjs/plugin/timezone';
import RelativeTime from 'dayjs/plugin/relativeTime';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

dayjs.extend(utc);
dayjs.extend(Timezone);
dayjs.extend(RelativeTime);
