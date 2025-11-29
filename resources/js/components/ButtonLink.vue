<script setup lang="ts">
	import { Link } from '@inertiajs/vue3'
	const props = defineProps<{
		label: string
		href: string
		variants?: 'success' | 'error' | 'warning' | 'info' | 'default' | 'primary'
	}>()

	const variations = {
		primary: 'bg-primary text-primary-foreground hover:bg-primary/90',
		success: 'bg-green-600 text-white hover:bg-green-600/90',
		error: 'bg-destructive text-white hover:bg-destructive/90',
		warning: 'bg-yellow-600 text-white hover:bg-yellow-600/90',
		info: 'bg-blue-600 text-white hover:bg-blue-600/90',
		default: 'bg-background hover:bg-accent hover:text-accent-foreground',
	}

	const variantClass = variations[props.variants || 'default']
</script>

<template>
	<Link
		class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium
			transition-all disabled:pointer-events-none disabled:opacity-50 outline-none border h-9 px-4 py-2
			[&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 has-[>svg]:px-3 cursor-pointer"
		:class="variantClass"
		:href="href"
	>
		<slot />
		{{ label }}
	</Link>
</template>

<style scoped></style>
