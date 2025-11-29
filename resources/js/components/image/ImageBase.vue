<template>
	<div
		:class="[
			'flex items-center justify-center overflow-hidden rounded',
			sizeClass,
			customClass,
			{'bg-gray-200': !src}
		]"
	>
		<img
			v-if="src"
			ref="imageRef"
			:src="src"
			:alt="alt"
			class="w-full h-full object-cover transition-opacity duration-300 opacity-100"
		/>

		<!-- Placeholder khi không có ảnh hoặc lỗi -->
		<div
			v-else
			class="w-full h-full flex items-center justify-center text-gray-400"
		>
			<svg
				class="w-10 h-10 text-gray-400"
				fill="currentColor"
				viewBox="0 0 24 24"
			>
				<path
					d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm0 2v8l4-4 4 4 6-6V6H4zm0 10l6-6 4 4 4-4 2 2v2H4z"
				></path>
			</svg>
		</div>
	</div>
</template>

<script lang="ts" setup>
	import { ref, computed, onMounted, onUnmounted, nextTick, type Ref } from 'vue'

	type SizeType = 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'custom'

	interface Props {
		src?: string
		alt?: string
		size?: SizeType
		width?: string
		height?: string
		customClass?: string
	}

	const props = withDefaults(defineProps<Props>(), {
		src: '',
		alt: 'Image',
		size: 'sm',
		width: '',
		height: '',
		customClass: '',
	})

	const imageRef: Ref<HTMLImageElement | null> = ref(null)
	const shouldLoad = ref<boolean>(false)
	const observer: Ref<IntersectionObserver | null> = ref(null)

	const sizeClass = computed<string>(() => {
		if (props.size === 'custom' && props.width && props.height) {
			return `w-[${props.width}] h-[${props.height}]`
		}

		const sizeMap: Record<SizeType, string> = {
			xs: 'w-16 h-16',
			sm: 'w-24 h-24',
			md: 'w-32 h-32',
			lg: 'w-48 h-48',
			xl: 'w-64 h-64',
			custom: 'w-32 h-32', // fallback
		}

		return sizeMap[props.size]
	})

	const registerObserver = () => {
		observer.value = new IntersectionObserver(
			(entries: IntersectionObserverEntry[]) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						shouldLoad.value = true
						observer.value?.unobserve(entry.target)
					}
				})
			},
			{ threshold: 0.1 }
		)

		if (imageRef.value?.parentElement) {
			observer.value.observe(imageRef.value.parentElement)
		}
	}

	onMounted(() => {
		if (!props.src) return

		nextTick(() => {
			registerObserver()
		})
	})

	onUnmounted(() => {
		observer.value?.disconnect()
	})
</script>
