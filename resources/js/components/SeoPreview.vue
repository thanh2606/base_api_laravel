<template>
	<div class="bg-white p-4 rounded-md border border-gray-200">
		<div>Ecommerce</div>
		<div class="text-xs mb-1">{{ usePage().props.ziggy.url }}</div>
		<div class="text-blue-500 text-xl mb-1 line-clamp-1">{{ props.title }}</div>
		<div class="text-sm line-clamp-3">{{ props.description }}</div>
	</div>
</template>

<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';

	const props = defineProps<{
		title?: string | null
		description?: string | null
		url?: string | null
	}>()
</script>
