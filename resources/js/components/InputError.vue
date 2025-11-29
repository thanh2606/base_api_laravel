<script setup lang="ts">
	defineProps<{
		message?: string
	}>()
</script>

<template>
	<div v-if="message">
		<p class="text-xs text-red-600 dark:text-red-500">
			{{ message }}
		</p>
	</div>
</template>
