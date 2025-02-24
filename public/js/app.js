import './bootstrap.js'
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

window.Echo.channel('post-votes')
    .listen('.vote.updated', (event) => {
        console.log('Vote Updated:', event);
        let voteCounter = document.querySelector(`#vote-count-${event.postId}`);
        if (voteCounter) {
            voteCounter.innerText = event.newCount;
        }
    });
