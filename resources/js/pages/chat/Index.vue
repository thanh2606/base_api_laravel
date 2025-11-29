<template>
	<AppLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">💬 Admin Chat System</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-6 text-gray-900">
						<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
							<!-- Danh sách Admins -->
							<div class="bg-white rounded-lg shadow p-4">
								<h3 class="text-lg font-semibold mb-4">👥 Danh sách Admins</h3>
								<div class="space-y-2">
									<div
										v-for="admin in admins"
										:key="admin.id"
										@click="selectAdmin(admin)"
										:class="[
											'p-3 rounded cursor-pointer transition-colors',
											selectedAdmin?.id === admin.id
												? 'bg-blue-100 border-blue-300 border'
												: 'bg-gray-50 hover:bg-gray-100',
										]"
									>
										<div class="flex items-center">
											<div
												class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold"
											>
												{{ admin.name.charAt(0).toUpperCase() }}
											</div>
											<div class="ml-3">
												<div class="font-medium">{{ admin.name }}</div>
												<div class="text-sm text-gray-500">{{ admin.email }}</div>
											</div>
											<div class="ml-auto">
												<div
													class="w-2 h-2 bg-green-400 rounded-full"
													title="Online"
												></div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Khung chat -->
							<div class="lg:col-span-2 bg-white rounded-lg shadow">
								<div
									v-if="!selectedAdmin"
									class="p-8 text-center text-gray-500"
								>
									<div class="text-6xl mb-4">💬</div>
									<h3 class="text-xl font-semibold mb-2">Chọn một admin để bắt đầu chat</h3>
									<p>Click vào admin bên trái để bắt đầu cuộc trò chuyện</p>
								</div>

								<div
									v-else
									class="flex flex-col h-96"
								>
									<!-- Header chat -->
									<div class="p-4 border-b bg-gray-50 rounded-t-lg">
										<div class="flex items-center">
											<div
												class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold"
											>
												{{ selectedAdmin.name.charAt(0).toUpperCase() }}
											</div>
											<div class="ml-3">
												<div class="font-semibold">{{ selectedAdmin.name }}</div>
												<div class="text-sm text-gray-500">{{ selectedAdmin.email }}</div>
											</div>
											<div class="ml-auto">
												<span
													v-if="isListening"
													class="text-green-600 text-sm"
													>🟢 Realtime</span
												>
												<span
													v-else
													class="text-gray-400 text-sm"
													>⭕ Đang kết nối...</span
												>
											</div>
										</div>
									</div>

									<!-- Khung tin nhắn -->
									<div
										class="flex-1 p-4 overflow-y-auto bg-gray-50"
										ref="messagesContainer"
									>
										<div
											v-if="messages.length === 0"
											class="text-center text-gray-500 py-8"
										>
											<div class="text-4xl mb-2">📝</div>
											<p>Chưa có tin nhắn nào. Hãy gửi tin nhắn đầu tiên!</p>
										</div>

										<div
											v-for="(message, index) in messages"
											:key="index"
											class="mb-4"
										>
											<div
												:class="[
													'max-w-xs p-3 rounded-lg',
													message.isOwn
														? 'bg-indigo-500 text-white ml-auto'
														: 'bg-white border shadow-sm',
												]"
											>
												<div
													v-if="!message.isOwn"
													class="text-xs text-gray-500 mb-1"
												>
													{{ message.user.name }}
												</div>
												<div>{{ message.message }}</div>
												<div
													:class="[
														'text-xs mt-1',
														message.isOwn ? 'text-indigo-100' : 'text-gray-400',
													]"
												>
													{{ message.timestamp }}
												</div>
											</div>
										</div>
									</div>

									<!-- Form gửi tin nhắn -->
									<div class="p-4 border-t bg-white">
										<form
											@submit.prevent="sendMessage"
											class="flex space-x-2"
										>
											<input
												v-model="newMessage"
												type="text"
												placeholder="Nhập tin nhắn cho admin..."
												class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
													focus:ring-indigo-500"
												:disabled="!isListening"
											/>
											<button
												type="submit"
												:disabled="!newMessage.trim() || !isListening || sending"
												class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 disabled:opacity-50
													disabled:cursor-not-allowed"
											>
												<span v-if="sending">📤</span>
												<span v-else>Gửi</span>
											</button>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!-- Status bar cho admin -->
						<div class="mt-6 bg-white rounded-lg shadow p-4">
							<h3 class="font-semibold mb-2">📊 Admin Chat Status:</h3>
							<div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
								<div>
									<strong>Admin hiện tại:</strong> {{ currentAdmin.name }} (ID:
									{{ currentAdmin.id }})
								</div>
								<div>
									<strong>Broadcasting channel:</strong>
									<span v-if="selectedAdmin">chat.{{ currentAdmin.id }}</span>
									<span
										v-else
										class="text-gray-400"
										>Chưa chọn</span
									>
								</div>
								<div>
									<strong>Connection:</strong>
									<span
										v-if="isListening"
										class="text-green-600"
										>🟢 Connected</span
									>
									<span
										v-else
										class="text-red-600"
										>🔴 Connecting...</span
									>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</AppLayout>
</template>

<script lang="ts" setup>
	import { ref, onMounted, onUnmounted, nextTick } from 'vue'
	import axios from 'axios'
	import AppLayout from '@/layouts/AppLayout.vue'

	interface Admin {
		id: number
		name: string
		email: string
	}

	interface Message {
		message: string
		user: Admin
		timestamp: string
		isOwn: boolean
	}

	// Props từ Laravel
	const props = defineProps<{
		admins: Admin[]
		currentAdmin: Admin
	}>()

	// Reactive data
	const selectedAdmin = ref<Admin | null>(null)
	const messages = ref<Message[]>([])
	const newMessage = ref('')
	const sending = ref(false)
	const isListening = ref(false)
	const messagesContainer = ref<HTMLElement | null>(null)

	// Echo instance cho admin
	let echoAdmin: any = null
	let currentChannel: string | null = null

	// Methods
	const selectAdmin = (admin: Admin) => {
		selectedAdmin.value = admin
		messages.value = [] // Reset messages khi chọn admin mới

		// Bắt đầu lắng nghe kênh cho admin hiện tại
		startListening()
	}

	const startListening = () => {
		try {
			// Lấy EchoAdmin từ window object
			echoAdmin = (window as any).EchoAdmin

			if (!echoAdmin) {
				console.error('EchoAdmin không được khởi tạo')
				return
			}

			// Dừng kênh cũ nếu có
			if (currentChannel) {
				echoAdmin.leaveChannel(currentChannel)
			}

			// Bắt đầu lắng nghe kênh mới cho admin
			currentChannel = `chat.${props.currentAdmin.id}`
			echoAdmin
				.private(currentChannel)
				.listen('.chat.message', (e: any) => {
					console.log('📨 Admin nhận tin nhắn mới:', e)

					messages.value.push({
						message: e.message,
						user: e.user,
						timestamp: e.timestamp,
						isOwn: false,
					})
					scrollToBottom()
				})
				.error((error: any) => {
					console.error('Lỗi lắng nghe kênh admin:', error)
					isListening.value = false
				})

			isListening.value = true
			console.log(`🎧 Admin đang lắng nghe kênh: ${currentChannel}`)
		} catch (error) {
			console.error('Lỗi kết nối admin chat:', error)
			isListening.value = false
		}
	}

	const sendMessage = () => {
		if (!newMessage.value.trim() || !selectedAdmin.value || sending.value) return

		sending.value = true

		// Thêm tin nhắn vào danh sách ngay lập tức (optimistic update)
		const messageData: Message = {
			message: newMessage.value,
			user: props.currentAdmin,
			timestamp: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
			isOwn: true,
		}

		messages.value.push(messageData)
		const messageToSend = newMessage.value
		newMessage.value = ''
		scrollToBottom()

		// Gửi tin nhắn qua admin route sử dụng axios
		axios
			.post(
				'/admin/chat/send',
				{
					message: messageToSend,
					receiver_id: selectedAdmin.value!.id,
				},
				{
					headers: {
						'X-CSRF-TOKEN': document
							.querySelector('meta[name="csrf-token"]')
							?.getAttribute('content'),
						'Content-Type': 'application/json',
						Accept: 'application/json',
					},
				}
			)
			.then((response) => {
				console.log('Admin tin nhắn đã được gửi thành công:', response.data)
				sending.value = false
			})
			.catch((error) => {
				console.error('Lỗi gửi tin nhắn admin:', error.response?.data || error.message)
				// Xóa tin nhắn nếu gửi thất bại
				messages.value.pop()
				sending.value = false
			})
	}

	const scrollToBottom = () => {
		nextTick(() => {
			if (messagesContainer.value) {
				messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
			}
		})
	}

	// Lifecycle
	onMounted(() => {
		console.log('💬 Admin Chat component loaded')
		console.log('Current admin:', props.currentAdmin)
		console.log('Available admins:', props.admins)

		// Đảm bảo có CSRF token
		if (!document.querySelector('meta[name="csrf-token"]')) {
			const meta = document.createElement('meta')
			meta.name = 'csrf-token'
			meta.content = '{{ csrf_token() }}'
			document.head.appendChild(meta)
		}
	})

	onUnmounted(() => {
		// Cleanup khi component bị destroy
		if (currentChannel && echoAdmin) {
			echoAdmin.leaveChannel(currentChannel)
			console.log(`🔇 Admin đã rời khỏi kênh: ${currentChannel}`)
		}
		console.log('💬 Admin Chat component unmounted')
	})
</script>

<style scoped>
	/* Custom scrollbar cho khung chat admin */
	.overflow-y-auto::-webkit-scrollbar {
		width: 4px;
	}

	.overflow-y-auto::-webkit-scrollbar-track {
		background: #f1f1f1;
	}

	.overflow-y-auto::-webkit-scrollbar-thumb {
		background: #c1c1c1;
		border-radius: 2px;
	}

	.overflow-y-auto::-webkit-scrollbar-thumb:hover {
		background: #a1a1a1;
	}
</style>
