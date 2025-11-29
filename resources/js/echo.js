import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher
// Tạo Echo cho admin với custom auth endpoint
window.EchoAdmin = new Echo({
	broadcaster: 'reverb',
	key: import.meta.env.VITE_REVERB_APP_KEY,
	wsHost: 'ecommerce.test',
	wsPort: 8080,
	forceTLS: false,
	enabledTransports: ['ws'],
	authEndpoint: '/admin/broadcasting/auth',
	auth: {
		headers: {
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
		},
	},
})

// // Echo mặc định cho user
// window.Echo = new Echo({
// 	broadcaster: 'reverb',
// 	key: import.meta.env.VITE_REVERB_APP_KEY,
// 	wsHost: 'ecommerce.test',
// 	wsPort: 8080,
// 	forceTLS: false,
// 	enabledTransports: ['ws'],
// })
